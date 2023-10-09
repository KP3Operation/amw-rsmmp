## 20231007

- [ ] Bagaimana cara menghitung pembayaran pending ("PendingFeeAmount")/ Apa arti items? Apakah total uang?
- [ ] Total uang yang telah terbayarkan tidak ada
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
- [ ] Inpatient list - SIMRS - Bagaimana menggunakan filter nama ruangan/kamar
    - ['Anggrek', 'Melati', 'ICU', 'Semua'] => 'Anggrek', 'Melati', 'ICU', 'Semua'
- [ ] Tipe CPPT ada apa saja(?)
- [ ] Overview pasien rawat inap
    - Tanggal di-card tanggal apa?
    - Bagaimana bisa mengambil data total pasien nya, (atm, the endpoint only to get data, we could do that, bu fetching the data and count the result, but it is not possible to get all the data of the patient, since we have the count filter value; or perhaps we could pass the count as 9999 but it is will make the process heavy)
- [ ] Dibeberapa endpoint ada parameter 'RecordNo', apakah kita ada nilai bawaan, atau kita harus impelentasi paginasi dari hasil record nya.