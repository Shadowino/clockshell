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
#include <DS1302.h>

#ifndef STASSID
#define STASSID "tp3020"
#define STAPSK  "1234567890"
#endif

//setting
#define DEBUGMODE false //DEBUG Mode, swith to true to enable Serial debug info !!Warning!! many massages!

// порт подключения реле
#define pin_relay 02
#define TIMEOUTCALL 30000 // froze time between calling

const char* ssid = STASSID;
const char* password = STAPSK;

char json[1024] = {}; // массив символов ???нужен???
const char* host = "http://10.0.1.26/api/today"; // uri для запросов API расписания
int answerCode = 0;

WiFiClient client;
HTTPClient http; // обьект http подключения wifi
DynamicJsonDocument payload(1024);
DS1302 rtc(5, 2, 4);
Time currTime;

/*
  запрос к серверу и запись полученного ответа от него
  в обьект DynamicJsonDocument payload(1024)

  возвращает код ответа (200|404|501)
*/
int httpReqwest() {
  if (!http.begin(client, host)) {                // HTTP запрос в network
    http.end();
    client.stop(); // ???
    return 0;
  }

  int httpCode = http.GET();                    // $httpCode is status qwestion (200 is OK 404 is not found)

  if (!(httpCode > 0)) {
    http.end();
    client.stop(); // ???
    return httpCode;
  }

  if (!(httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_MOVED_PERMANENTLY)) // file not found at server
  {
    http.end();
    client.stop(); // ???
    return httpCode;
  }

  http.getString().toCharArray(json, 1024);             // http answer to char array
  deserializeJson(payload, json);                       // write string answer to json object

  http.end();
  client.stop(); // ???
  return httpCode;
}


/*
  compareTime() сравнивает текущее время с расписанием

  возвращает истинну если время найденно
*/
bool compareTime() {
  currTime = rtc.getTime();
  //String currTimeStr = currTime.hour + ":" + currTime.min; // время в МК в формате "чч:мм"
  String currTimeStr = "04:59"; // время в МК в формате "чч:мм"
  String compTime = "";
  Serial.print("current time:");
  Serial.println(currTimeStr);

  if (payload["1"][0].isNull()) return false;
  for (int d = 1; d < 5; d++) // поиск в расписании текущего времени (+-diff в минутах)
  {
    for (int t = 0; t < 4; t++) {
      // d - номер пары в str
      // t - номер звонка в int
      if (payload[String(d)][t].isNull()) continue;
      compTime = payload[String(d)][t].as<String>();
      Serial.print("comare time:");
      Serial.println(compTime);
      if (compTime == currTimeStr) {
        Serial.println("Found! Send Signal!!!");
        return true;
      }
    }
  }

  return false;
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
  answerCode = httpReqwest();
  if (answerCode == 200) {
    Serial.print("getting data:\n");
    for (int d = 1; d < 5; d++) {
      Serial.print(d);
      Serial.print(":\n");
      for (int t = 0; t < 4; t++) {
        Serial.print("-");
        Serial.println(payload[String(d)][t].as<String>());
      }
    }
  } else {
    Serial.print("http reqwest return incorrect answer! code:");
    Serial.println(answerCode);
  }
  if (compareTime()){
    Serial.println("Time detect in loop! Call!");
    delay(TIMEOUTCALL);
  }

  Serial.println();
  Serial.println("closing connection");
  delay(5000);
}
