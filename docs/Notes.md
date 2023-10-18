## 20231017

### Notes
- Appointment Logic -> local db save appointmentNo, appointmentDate, serviceUnitId, -> sync request with simrs

## 20231009

### Questions
- [ ] Bagaimana cara mendapatkan 'Dosis Obat' pada halaman detail resep obat
- [ ] Bagaimana cara mendapatkan nama 'Dokter Pemeriksa' pada tab halaman hasil lab
- [ ] Response data simrs untuk labResult tidak sama antara response asli dengan response contoh
- [ ] if it is possible please provide the response type for every property for easy data type parsing
- [ ] tanggal keluar, jam keluar - encounter detail page
- [ ] apakah ada kemungkinan no medis antara satu pasien dengan yang lain sama?
- [ ] Bagaimana mendapatkan jam awal dan jam akhir untuk hari sabtu - halaman jadwal dokter

## 20231007

### Questions
- [x] Bagaimana cara menghitung pembayaran pending ("PendingFeeAmount")/ Apa arti items? Apakah total uang?
- [x] Total uang yang telah terbayarkan tidak ada
```
{
  "registrationNo": "REG/EM/230623-0010",
  "medicalNo": "035156",
  "patientName": "SONDANG VIRGINIA, NN",
  "itemName": "KONSULTASI/PEMERIKSAAN IGD",
  "qty": 1,
  "guarantorName": "UMUM",
  "paymentPercentage": 100
}
```
- [x] Inpatient list - SIMRS - Bagaimana menggunakan filter nama ruangan/kamar
    - ['Anggrek', 'Melati', 'ICU', 'Semua'] => 'Anggrek', 'Melati', 'ICU', 'Semua'
- [x] Tipe CPPT ada apa saja(?)
- [x] Overview pasien rawat inap
    - Tanggal di-card tanggal apa?
    - Bagaimana bisa mengambil data total pasien nya, (atm, the endpoint only to get data, we could do that, bu fetching the data and count the result, but it is not possible to get all the data of the patient, since we have the count filter value; or perhaps we could pass the count as 9999 but it is will make the process heavy)
- [x] Dibeberapa endpoint ada parameter 'RecordNo', apakah kita ada nilai bawaan, atau kita harus impelentasi paginasi dari hasil record nya.
