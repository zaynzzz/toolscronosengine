import serial
import requests
import time

# Buka koneksi serial ke GPS
ser = serial.Serial('COM3', 9600, timeout=1)  # Sesuaikan 'COM3' dengan port serial Anda

def get_gps_data():
    time.sleep(2)  # Tunggu 2 detik sebelum mulai membaca
    while True:
        line = ser.readline().decode('utf-8').strip()
        print(f"Received: {line}")
        if line.startswith('$GPGGA'):  # Contoh: cek tipe kalimat NMEA (GGA)
            data = line.split(',')
            if data[2] and data[4]:  # Memastikan data latitude dan longitude tersedia
                lat = float(data[2])  # Latitude
                lon = float(data[4])  # Longitude
                # Konversi format jika diperlukan
                lat = convert_to_decimal(lat, data[3])  # Data[3] adalah arah N/S
                lon = convert_to_decimal(lon, data[5])  # Data[5] adalah arah E/W
                return lat, lon

def convert_to_decimal(degree_minutes, direction):
    """ Konversi format derajat-menit ke desimal """
    degrees = int(degree_minutes[:2])
    minutes = float(degree_minutes[2:])
    decimal = degrees + minutes / 60
    if direction in ['S', 'W']:
        decimal = -decimal
    return decimal

# Fungsi untuk mengirim data ke server PHP di localhost
def send_data_to_php(lat, lon):
    url = 'http://localhost/webhook/gps_receiver.php'  # URL localhost ke PHP
    data = {
        'latitude': lat,
        'longitude': lon
    }
    response = requests.post(url, data=data)
    print(response.text)  # Cek respons dari server
    return response.text

# Main loop untuk mengambil data GPS dan mengirimkannya ke server PHP
while True:
    try:
        lat, lon = get_gps_data()
        if lat is not None and lon is not None:  # Pastikan latitude dan longitude tidak None
            print(f"Latitude: {lat}, Longitude: {lon}")
            response = send_data_to_php(lat, lon)
            print(f"Response from server: {response}")
        else:
            print("Latitude or Longitude not found.")
        time.sleep(1)  # Tunggu 1 detik sebelum mencoba lagi
    except Exception as e:
        print(f"Error: {e}")
