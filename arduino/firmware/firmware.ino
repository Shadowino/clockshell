/*
  прошивка для esp8266
  create date - 04.09.2022
  WASDO:
  рефакторинг кода
  добавлена функция получения расписания см httpReqwest()
  TODO:
  обновить loop()
  переделать архитектуру
*/

#include <ESP8266WiFi.h>
#include <ArduinoJson.h>

#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>

#ifndef STASSID
#define STASSID "tp3020"
#define STAPSK  "1234567890"
#endif

//setting
#define DEBUGMODE true //DEBUG Mode, swith to true to enable Serial debug info !!Warning!! many massages!

// порт подключения реле
#define pin_relay 02

const char* ssid = STASSID;
const char* password = STAPSK;

char json[1024] = {}; // массив символов ???нужен???

const char* host = "http://10.0.1.26/api/today"; // uri для запросов API расписания

WiFiClient client;
HTTPClient http; // обьект http подключения wifi
DynamicJsonDocument payload(1024);

/*
  запрос к серверу и запись полученного ответа от него
  в обьект DynamicJsonDocument payload(1024)

  возвращает код ответа (200|404|501)
*/
int httpReqwest() {
  if (DEBUGMODE) {                        // debug mode
    Serial.print("connecting to ");
    Serial.println(host);
    Serial.print("[HTTP] begin...\n");
  }

  if (!http.begin(client, host)) {                // HTTP запрос в network
    Serial.printf("[HTTP} Unable to connect %s\n", host);
    http.end();
    Serial.print("closing connection\n");
    client.stop(); // ???
    return 0;
  }

  if (DEBUGMODE) Serial.print("[HTTP] GET...\n");
  int httpCode = http.GET();                    // $httpCode is status qwestion (200 is OK 404 is not found)

  if (!(httpCode > 0)) {
    Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
    http.end();
    Serial.print("closing connection\n");
    client.stop(); // ???
    return httpCode;
  }

  if (DEBUGMODE) Serial.printf("[HTTP] GET... code: %d\n", httpCode);

  if (!(httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_MOVED_PERMANENTLY)) // file not found at server
  {
    Serial.printf("[HTTP] GET... complete, error answer: %d\n", httpCode);
    http.end();
    Serial.print("closing connection\n");
    client.stop(); // ???
    return httpCode;
  }

  http.getString().toCharArray(json, 1024);             // http answer to char array
  //Serial.printf("http data:", json);                // answer from server
  deserializeJson(payload, json);                   // write string answer to json object

  if (DEBUGMODE)                            // в режиме DEBUG show answer with json
  {
    Serial.print("getting data:\n");
    for (int d = 1; d < 5; d++) {
      // Serial.print(payload[String(d)][0].as<String>());
      Serial.print(d);
      Serial.print(":\n");
      for (int t = 0; t < 4; t++) {
        Serial.print("-");
        Serial.println(payload[String(d)][t].as<String>());
      }
    }
  }

  http.end();
  Serial.print("closing connection\n");
  client.stop(); // ???
  delay(5000);
  return httpCode;
}

void setup() {
  Serial.begin(115200);
  Serial.print("!> Serial debug start\n");

  // We start by connecting to a WiFi network
  Serial.print("\nConnecting to '");
  Serial.print(ssid);
  Serial.print("' With password:");
  Serial.println(password);

  WiFi.mode(WIFI_STA); // настройка режима wifi
  WiFi.begin(ssid, password); // подключение к сети по ssid pasword

  while (WiFi.status() != WL_CONNECTED) { // ожидание подключения wifi
    delay(500);
    Serial.print(".");
  }

  Serial.print("WiFi connected\n");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP()); // выводим ip adress модуля wifi}
}

void loop() {
  if (httpReqwest() == 200) {
    Serial.print("getting data:\n");
    for (int d = 1; d < 5; d++) {
      //          Serial.print(payload[String(d)][0].as<String>());
      Serial.print(d);
      Serial.print(":\n");
      for (int t = 0; t < 4; t++) {
        Serial.print("-");
        Serial.println(payload[String(d)][t].as<String>());
      }
    }
  }
  Serial.println();
  Serial.println("closing connection");
  client.stop();
  delay(5000);
}
