#include <SPI.h>
#include <MFRC522.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "AZHER";
const char* password = "123StalLPlayZ";

String serverName = "http://192.168.1.2:8000/api/alarm-log";
// =================================================================


// --- Definisi Pin ---
#define SMOKE_SENSOR_PIN 34  // Pin A0 dari MQ-2
#define BUZZER_PIN 15        // Pin I/O dari Buzzer Pasif
#define RST_PIN 22           // Pin RST RFID
#define SS_PIN 5             // Pin SDA/SS RFID
#define ONE_WIRE_BUS 4       // Pin Data DS18B20
#define LED_GREEN 32         // Kaki panjang LED Hijau
#define LED_RED 33           // Kaki panjang LED Merah

MFRC522 rfid(SS_PIN, RST_PIN);
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

int batasAsap = 300;
float batasSuhu = 50.0;
bool isAlarmActive = false;

// --- FUNGSI TRANSMISI DATA KE LARAVEL ---
void kirimDataKeLaravel(String status, float suhu, int asap, String rfid_uid) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverName);
    http.addHeader("Content-Type", "application/json");

    // Struktur Payload JSON
    String jsonPayload = "{\"room_name\":\"F205\",\"smoke_level\":" + String(asap) + ",\"temperature\":" + String(suhu) + ",\"status\":\"" + status + "\",\"resolved_by_rfid\":\"" + (rfid_uid == "NONE" ? "" : rfid_uid) + "\"}";

    Serial.println("=> Mengirim data ke Server: " + jsonPayload);
    int httpResponseCode = http.POST(jsonPayload);

    if (httpResponseCode > 0) {
      Serial.println("=> Respon Server: HTTP " + String(httpResponseCode));
    } else {
      Serial.println("=> Error kirim data: " + http.errorToString(httpResponseCode));
    }
    http.end();
  } else {
    Serial.println("=> WiFi Terputus! Gagal mengirim log.");
  }
}

void setup() {
  Serial.begin(115200);

  pinMode(BUZZER_PIN, OUTPUT);
  pinMode(LED_GREEN, OUTPUT);
  pinMode(LED_RED, OUTPUT);

  digitalWrite(LED_GREEN, HIGH);
  digitalWrite(LED_RED, LOW);

  SPI.begin();
  rfid.PCD_Init();
  sensors.begin();

  // --- INISIALISASI KONEKSI WIFI ---
  Serial.println("\n==================================");
  Serial.print("Menghubungkan ke WiFi: ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi Terhubung!");
  Serial.print("IP Address ESP32: ");
  Serial.println(WiFi.localIP());
  Serial.println("Sistem Keamanan Sarpras Aktif!");
  Serial.println("==================================\n");
}

void loop() {
  int levelAsap = analogRead(SMOKE_SENSOR_PIN);
  sensors.requestTemperatures();
  float suhuSekarang = sensors.getTempCByIndex(0);

  static unsigned long lastPrint = 0;
  if (millis() - lastPrint > 2000 && !isAlarmActive) {
    Serial.println("Status: Aman | Suhu: " + String(suhuSekarang) + "°C | Asap: " + String(levelAsap));
    lastPrint = millis();
  }

  // --- LOGIKA DETEKSI BAHAYA ---
  if ((levelAsap > batasAsap || suhuSekarang > batasSuhu) && !isAlarmActive) {
    Serial.println("\n🔥 BAHAYA KEBAKARAN TERDETEKSI! 🔥");
    isAlarmActive = true;
    digitalWrite(LED_GREEN, LOW);
    digitalWrite(LED_RED, HIGH);

    kirimDataKeLaravel("BAHAYA", suhuSekarang, levelAsap, "NONE");
  }

  // --- LOGIKA AUTENTIKASI DAN RESET ALARM ---
  if (isAlarmActive) {
    tone(BUZZER_PIN, 1000);

    // Pemindaian Kartu RFID Administrator
    if (rfid.PICC_IsNewCardPresent() && rfid.PICC_ReadCardSerial()) {
      String uidKartu = "";
      for (byte i = 0; i < rfid.uid.size; i++) {
        uidKartu += String(rfid.uid.uidByte[i] < 0x10 ? "0" : "");
        uidKartu += String(rfid.uid.uidByte[i], HEX);
      }
      uidKartu.toUpperCase();

      Serial.println("\nKartu Admin di-tap! UID: " + uidKartu);

      noTone(BUZZER_PIN);
      digitalWrite(LED_RED, LOW);
      isAlarmActive = false;

      kirimDataKeLaravel("DIMATIKAN_ADMIN", suhuSekarang, levelAsap, uidKartu);

      Serial.println("Menunggu suhu/asap turun...");
      while (analogRead(SMOKE_SENSOR_PIN) > batasAsap || sensors.getTempCByIndex(0) > batasSuhu) {
        sensors.requestTemperatures();
        digitalWrite(LED_RED, HIGH);
        delay(300);
        digitalWrite(LED_RED, LOW);
        delay(1700);
      }

      Serial.println("Kondisi lingkungan normal. Sistem kembali standby.\n");
      digitalWrite(LED_GREEN, HIGH);
      digitalWrite(LED_RED, LOW);
      rfid.PICC_HaltA();
    }
  } else {
    noTone(BUZZER_PIN);
  }
  delay(100);
}