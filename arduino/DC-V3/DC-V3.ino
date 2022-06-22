// подключение к wifi и get запрос к серверу (надо тестировать)
// нужен wifi!!!!
#include <ESP8266WiFi.h>
#include <ArduinoJson.h>

#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>

#ifndef STASSID
#define STASSID "SSID"
#define STAPSK  "1234567890"
#endif

const char* ssid     = STASSID;
const char* password = STAPSK;

char json[1024] = {};

//const char* host = "http://192.168.43.14/shedule.php?list"; // ip адресс сервера для получения расписания
const char* host = "http://www.srv175113.hoster-test.ru/api/schedile/1";
const uint16_t port = 17;

void setup() {
  Serial.begin(115200);

  // We start by connecting to a WiFi network
  Serial.println("");
  Serial.print("Connecting to '");
  Serial.print(ssid);
  Serial.print("' With password:");
  Serial.println(password);

  WiFi.mode(WIFI_STA); // настройка режима wifi
  WiFi.begin(ssid, password); // подключение к сети по ssid pasword

  while (WiFi.status() != WL_CONNECTED) { // ожидание подключения wifi
    delay(500);
    Serial.print(".");
  }

  Serial.println("WiFi connected");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP()); // выводим ip adress модуля wifi
}

void loop() {
  Serial.print("connecting to ");
  Serial.println(host);
  //  Serial.print(':');
  //  Serial.println(port);
  WiFiClient client;

  HTTPClient http; // обьект http подключения wifi

  Serial.print("[HTTP] begin...\n");
  if (http.begin(client, host)) {  // HTTP запрос в network


    Serial.print("[HTTP] GET...\n");
    int httpCode = http.GET(); // $httpCode is status qwestion (200 is OK 404 is not found)

    if (httpCode > 0) {
      Serial.printf("[HTTP] GET... code: %d\n", httpCode);

      // file found at server
      if (httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_MOVED_PERMANENTLY) {
        http.getString().toCharArray(json, 1024);

        DynamicJsonDocument payload(1024);
        deserializeJson(payload, json);

        for (int i = 0; i < payload["times"].size(); i++) {
          Serial.print(payload["times"][i]["clock"].as<int>());
          Serial.print(":");
          Serial.println(payload["times"][i]["minutes"].as<int>());
        }
      }
    } else {
      Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
    }

    http.end();
  } else {
    Serial.printf("[HTTP} Unable to connect\n");
  }

  // Close the connection
  Serial.println();
  Serial.println("closing connection");
  client.stop();
  delay(5000);

}
