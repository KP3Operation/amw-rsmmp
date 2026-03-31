<template>
  <div class="pp-wrap" :class="'lang-' + lang">

    <!-- TOPBAR -->
    <header class="pp-topbar">
      <div class="pp-topbar-left">
        <button class="pp-hamburger" @click="$router.back()" aria-label="Kembali">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6" />
          </svg>
        </button>
        <span class="pp-topbar-title">{{ t('topbarTitle') }}</span>
      </div>
      <div class="pp-lang-toggle">
        <button :class="['pp-lang-btn', { active: lang === 'id' }]" @click="setLang('id')">ID</button>
        <button :class="['pp-lang-btn', { active: lang === 'en' }]" @click="setLang('en')">EN</button>
      </div>
    </header>

    <!-- PAGE BODY -->
    <div class="pp-body">

      <!-- MAIN -->
      <main class="pp-content">

        <!-- HEADER -->
        <div class="pp-content-header">
          <h1>{{ t('title') }}</h1>
          <p class="pp-effective">{{ t('effective') }}</p>
        </div>

        <!-- SEC 1 -->
        <section class="pp-section" id="sec1">
          <h2>{{ t('sec1.title') }}</h2>
          <p v-html="t('sec1.p1')" />
          <p v-html="t('sec1.p2')" />
          <div class="pp-note" v-html="t('sec1.note')" />
        </section>

        <!-- SEC 2 -->
        <section class="pp-section" id="sec2">
          <h2>{{ t('sec2.title') }}</h2>
          <p>{{ t('sec2.intro') }}</p>
          <table class="pp-table">
            <thead>
              <tr>
                <th v-for="col in t('sec2.cols')" :key="col" :style="col === t('sec2.cols')[0] ? 'width:24%' : col === t('sec2.cols')[2] ? 'width:30%' : ''">{{ col }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in t('sec2.rows')" :key="row[0]">
                <td><strong>{{ row[0] }}</strong></td>
                <td>{{ row[1] }}</td>
                <td class="pp-col-hide">{{ row[2] }}</td>
              </tr>
            </tbody>
          </table>
        </section>

        <!-- SEC 3 -->
        <section class="pp-section" id="sec3">
          <h2>{{ t('sec3.title') }}</h2>
          <p>{{ t('sec3.intro') }}</p>
          <ul class="pp-list">
            <li v-for="item in t('sec3.items')" :key="item">{{ item }}</li>
          </ul>
          <div class="pp-note" v-html="t('sec3.note')" />
        </section>

        <!-- SEC 4 -->
        <section class="pp-section" id="sec4">
          <h2>{{ t('sec4.title') }}</h2>
          <p>{{ t('sec4.intro') }}</p>
          <div class="table-responsive">
            <table class="pp-table">
              <thead>
                <tr>
                  <th style="width:32%">{{ t('sec4.cols')[0] }}</th>
                  <th style="width:48%">{{ t('sec4.cols')[1] }}</th>
                  <th style="width:20%">{{ t('sec4.cols')[2] }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="row in t('sec4.rows')" :key="row[0]">
                  <td><strong>{{ row[0] }}</strong></td>
                  <td>{{ row[1] }}</td>
                  <td><span :class="['pp-badge', row[2].class]">{{ row[2].label }}</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- SEC 5 -->
        <section class="pp-section" id="sec5">
          <h2>{{ t('sec5.title') }}</h2>
          <p>{{ t('sec5.intro') }}</p>
          <table class="pp-table">
            <thead>
              <tr>
                <th style="width:38%">{{ t('sec5.cols')[0] }}</th>
                <th>{{ t('sec5.cols')[1] }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in t('sec5.rows')" :key="row[0]">
                <td><strong>{{ row[0] }}</strong></td>
                <td v-html="row[1]" />
              </tr>
            </tbody>
          </table>
          <div class="pp-note">{{ t('sec5.note') }}</div>
        </section>

        <!-- SEC 6 -->
        <section class="pp-section" id="sec6">
          <h2>{{ t('sec6.title') }}</h2>
          <p>{{ t('sec6.intro') }}</p>
          <table class="pp-table">
            <thead>
              <tr>
                <th style="width:32%">{{ t('sec6.cols')[0] }}</th>
                <th style="width:28%">{{ t('sec6.cols')[1] }}</th>
                <th>{{ t('sec6.cols')[2] }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in t('sec6.rows')" :key="row[0]">
                <td><strong>{{ row[0] }}</strong></td>
                <td>{{ row[1] }}</td>
                <td>{{ row[2] }}</td>
              </tr>
            </tbody>
          </table>
          <p v-html="t('sec6.footer')" />
        </section>

        <!-- SEC 7 -->
        <section class="pp-section" id="sec7">
          <h2>{{ t('sec7.title') }}</h2>
          <p>{{ t('sec7.intro') }}</p>
          <table class="pp-table">
            <thead>
              <tr>
                <th style="width:36%">{{ t('sec7.cols')[0] }}</th>
                <th>{{ t('sec7.cols')[1] }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in t('sec7.rows')" :key="row[0]">
                <td><strong>{{ row[0] }}</strong></td>
                <td>{{ row[1] }}</td>
              </tr>
            </tbody>
          </table>
        </section>

        <!-- SEC 8 -->
        <section class="pp-section" id="sec8">
          <h2>{{ t('sec8.title') }}</h2>
          <p v-html="t('sec8.p1')" />
          <p>{{ t('sec8.p2') }}</p>
        </section>

        <!-- SEC 9 -->
        <section class="pp-section" id="sec9">
          <h2>{{ t('sec9.title') }}</h2>
          <p>{{ t('sec9.intro') }}</p>
          <ul class="pp-list">
            <li v-for="item in t('sec9.items')" :key="item">{{ item }}</li>
          </ul>
          <p>{{ t('sec9.footer') }}</p>
        </section>

        <!-- SEC 10 -->
        <section class="pp-section" id="sec10">
          <h2>{{ t('sec10.title') }}</h2>
          <p>{{ t('sec10.intro') }}</p>
          <div class="pp-contact-card">
            <h3>RS Mitra Medika Premiere</h3>
            <p class="pp-contact-subtitle">{{ t('sec10.subtitle') }}</p>
            <div class="pp-contact-grid">
              <div class="pp-contact-row" v-for="item in t('sec10.contacts')" :key="item.label">
                <span class="pp-c-label">{{ item.label }}</span>
                <span class="pp-c-val">
                  <a v-if="item.href" :href="item.href" :target="item.target || '_self'" v-html="item.value" />
                  <span v-else v-html="item.value" />
                </span>
              </div>
            </div>
            <div class="pp-contact-divider" />
            <p class="pp-response-note" v-html="t('sec10.responseNote')" />
          </div>
        </section>

      </main>
    </div>

    <!-- FOOTER -->
    <footer class="pp-footer">
      <div class="pp-footer-brand">
        <div class="pp-footer-logo">
          <i class="bi bi-hospital text-white"></i>
        </div>
        <span>RS Mitra Medika Premiere</span>
      </div>
      <p>© {{ year }} RS Mitra Medika Premiere. {{ t('footerRights') }} &nbsp;·&nbsp; <a href="https://mitramedika.com">mitramedika.com</a></p>
    </footer>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const lang = ref('id')
const activeSection = ref('sec1')
const year = new Date().getFullYear()

const setLang = (l) => { lang.value = l }

// ─── i18n ───────────────────────────────────────────────────────────────────
const messages = {
  id: {
    topbarTitle: 'Kebijakan Privasi',
    contents: 'Daftar Isi',
    title: 'Kebijakan Privasi Aviat Mobile Web',
    effective: 'Berlaku Sejak: 1 Maret 2026',
    footerRights: 'Semua hak dilindungi.',

    sec1: {
      title: '1. Informasi Umum',
      p1: 'Kebijakan Privasi ini menjelaskan bagaimana <strong>Aviat Mobile Web</strong> ("Aplikasi", "kami") yang dioperasikan oleh <strong>RS Mitra Medika Premiere</strong> mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi Anda ketika menggunakan layanan kami melalui peramban web di perangkat Android maupun iOS.',
      p2: 'Aplikasi ini adalah platform layanan kesehatan digital yang melayani dua jenis pengguna: <strong>Pasien</strong> — untuk membuat janji temu, melihat riwayat kunjungan, hasil laboratorium, riwayat resep, dan grafik tanda vital; serta <strong>Dokter</strong> — untuk mengelola daftar janji temu, daftar pasien rawat inap, catatan medis, dan ringkasan biaya.',
      note: '<strong>Penting:</strong> Dengan mengakses atau menggunakan Aplikasi ini, Anda menyatakan telah membaca, memahami, dan menyetujui seluruh ketentuan dalam Kebijakan Privasi ini. Jika Anda tidak menyetujuinya, harap hentikan penggunaan Aplikasi.',
    },

    sec2: {
      title: '2. Data yang Kami Kumpulkan',
      intro: 'Untuk memberikan layanan kesehatan digital yang optimal, kami mengumpulkan kategori data berikut berdasarkan jenis pengguna:',
      cols: ['Kategori', 'Jenis Data', 'Cara Pengumpulan'],
      rows: [
        ['Identitas Pasien', 'Nama lengkap, NIK/KTP, nomor telepon/WhatsApp', 'Diisi langsung saat registrasi'],
        ['Identitas Dokter', 'Nama lengkap, nomor telepon/WhatsApp, ID Dokter', 'Diisi langsung saat registrasi dan divalidasi dari SIMRS'],
        ['Data Medis Pasien', 'Riwayat janji temu, riwayat kunjungan, hasil laboratorium, riwayat resep, grafik tanda vital', 'Otomatis dari sistem SIMRS'],
        ['Data Notifikasi', 'Token perangkat untuk pengiriman notifikasi janji temu dan pengingat', 'Otomatis dari sistem saat login'],
      ],
    },

    sec3: {
      title: '3. Tujuan Penggunaan Data',
      intro: 'Data yang kami kumpulkan digunakan untuk tujuan-tujuan berikut:',
      items: [
        'Memproses pendaftaran akun dan verifikasi identitas pengguna melalui OTP WhatsApp',
        'Memungkinkan pasien membuat, melihat, dan membatalkan janji temu dengan dokter',
        'Menampilkan jadwal dokter yang tersedia beserta unit pelayanan dan jam operasional',
        'Mengirimkan notifikasi konfirmasi, pengingat, serta perubahan atau pembatalan janji temu',
        'Menampilkan riwayat kunjungan, hasil laboratorium, riwayat resep, dan grafik tanda vital pasien',
        'Memungkinkan dokter mengakses daftar janji temu, daftar rawat inap, catatan medis, dan ringkasan biaya',
        'Menyinkronkan data dengan Sistem Informasi Manajemen Rumah Sakit (SIMRS)',
        'Memenuhi kewajiban hukum dan regulasi di bidang kesehatan',
        'Memberikan dukungan teknis kepada pengguna',
      ],
      note: 'Kami <strong>tidak menggunakan</strong> data Anda untuk periklanan pihak ketiga, profiling komersial, atau penjualan data dalam bentuk apapun.',
    },

    sec4: {
      title: '4. Berbagi Data dengan Pihak Ketiga',
      intro: 'Kami tidak menjual atau menyewakan data pribadi Anda. Data hanya dibagikan dalam kondisi berikut:',
      cols: ['Penerima Data', 'Tujuan Pembagian', 'Dasar'],
      rows: [
        ['Tenaga medis & staf RS Mitra Medika Premiere', 'Pemberian layanan kesehatan langsung kepada pasien, termasuk akses data janji temu dan catatan medis', { label: 'Layanan medis', class: 'badge-medical' }],
        ['Penyedia layanan teknologi', 'Mitra teknis pendukung operasional aplikasi — terikat perjanjian kerahasiaan', { label: 'Kontrak & NDA', class: 'badge-contract' }],
        ['Otoritas pemerintah', 'Kewajiban hukum atau regulasi yang berlaku (termasuk Kemenkes dan BPJS Kesehatan)', { label: 'Kewajiban hukum', class: 'badge-legal' }],
      ],
    },

    sec5: {
      title: '5. Keamanan Data',
      intro: 'Kami menerapkan langkah-langkah teknis dan organisasional sesuai standar industri untuk melindungi data Anda, termasuk:',
      cols: ['Langkah Keamanan', 'Keterangan'],
      rows: [
        ['Enkripsi data transit', 'Seluruh komunikasi dienkripsi menggunakan <strong>HTTPS/TLS</strong> melalui Cloudflare SSL'],
        ['Enkripsi data tersimpan', 'Data sensitif dienkripsi pada penyimpanan menggunakan fitur enkripsi bawaan Laravel'],
        ['Autentikasi OTP WhatsApp', 'Login menggunakan kode OTP yang dikirim ke nomor WhatsApp terdaftar — tanpa kata sandi'],
        ['Perlindungan CDN & Firewall', 'Cloudflare digunakan sebagai CDN dan lapisan firewall untuk mencegah serangan DDoS dan akses berbahaya'],
        ['Pemantauan & audit log', 'Pemantauan keamanan dan pencatatan aktivitas sistem dilakukan secara berkala'],
        ['Pengujian keamanan', 'Pengujian keamanan aplikasi dilakukan secara periodik'],
      ],
      note: 'Tidak ada sistem yang 100% kebal dari ancaman. Jika Anda mencurigai adanya akses tidak sah ke akun Anda, segera hubungi kami.',
    },

    sec6: {
      title: '6. Retensi & Penghapusan Data',
      intro: 'Kami menyimpan data Anda selama diperlukan atau diwajibkan oleh hukum yang berlaku:',
      cols: ['Jenis Data', 'Durasi Penyimpanan', 'Dasar'],
      rows: [
        ['Data akun aktif', 'Selama akun aktif', 'Kebutuhan layanan'],
        ['Rekam medis & data klinis', 'Minimal 5 tahun', 'Permenkes No. 24 Tahun 2022'],
        ['Log aktivitas', 'Maksimal 12 bulan', 'Keamanan sistem'],
        ['Data transaksi', 'Minimal 5 tahun', 'Ketentuan perpajakan'],
      ],
      footer: 'Permintaan penghapusan akun dapat diajukan dengan menghubungi tim kami. Permintaan akan diproses dalam <strong>30 hari kerja</strong>.',
    },

    sec7: {
      title: '7. Hak-Hak Anda',
      intro: 'Sesuai UU PDP dan regulasi terkait, Anda memiliki hak berikut atas data pribadi Anda:',
      cols: ['Hak', 'Keterangan'],
      rows: [
        ['Hak Akses', 'Meminta salinan data pribadi yang kami miliki tentang Anda'],
        ['Hak Koreksi', 'Meminta perbaikan data yang tidak akurat atau tidak lengkap'],
        ['Hak Penghapusan', 'Meminta penghapusan akun dan data, sesuai batasan hukum yang berlaku'],
        ['Hak Portabilitas', 'Menerima data dalam format yang dapat dibaca oleh mesin'],
        ['Hak Keberatan', 'Mengajukan keberatan atas pemrosesan data untuk tujuan tertentu'],
        ['Hak Penarikan Persetujuan', 'Mencabut izin kapan saja dengan menghubungi tim kami atau melalui pengaturan akun Anda'],
      ],
    },

    sec8: {
      title: '8. Kebijakan Perlindungan Anak',
      p1: 'Aplikasi ini tidak dirancang untuk digunakan secara mandiri oleh anak di bawah usia <strong>17 tahun</strong>. Kami tidak secara sengaja mengumpulkan data pribadi dari anak-anak tanpa persetujuan orang tua atau wali yang sah.',
      p2: 'Apabila pasien adalah anak-anak, pendaftaran dan penggunaan aplikasi wajib dilakukan oleh orang tua atau wali yang berwenang. Jika Anda menemukan data anak yang dikumpulkan tanpa izin, segera hubungi kami.',
    },

    sec9: {
      title: '9. Perubahan Kebijakan Privasi',
      intro: 'Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Setiap perubahan yang bersifat material akan dikomunikasikan melalui:',
      items: [
        'Notifikasi dalam Aplikasi',
        'Pemberitahuan saat Anda membuka Aplikasi setelah pembaruan',
        'Pembaruan tanggal "Berlaku" di bagian atas halaman ini',
      ],
      footer: 'Penggunaan Aplikasi setelah tanggal efektif perubahan dianggap sebagai penerimaan Anda atas kebijakan yang telah diperbarui.',
    },

    sec10: {
      title: '10. Hubungi Kami',
      intro: 'Jika Anda memiliki pertanyaan atau permintaan terkait Kebijakan Privasi ini, silakan hubungi tim kami:',
      subtitle: 'Tim Privasi & Layanan Digital',
      contacts: [
        { label: 'Alamat', value: 'Jl. Let. Jend. S. Parman No.236,<br>Medan, Sumatera Utara' },
        { label: 'Telepon', value: '(061) – 4502021', href: 'tel:+62614502021' },
        { label: 'WhatsApp (24 Jam)', value: '+62 811-328-135', href: 'https://wa.me/628113281358' },
        { label: 'IGD (24 Jam)', value: '+62 813-7618-7341', href: 'tel:+6281376187341' },
        { label: 'Website', value: 'mitramedika.com', href: 'https://mitramedika.com', target: '_blank' },
        { label: 'Instagram', value: '@rsmitramedikapremiere', href: 'https://instagram.com/rsmitramedikapremiere', target: '_blank' },
      ],
      responseNote: 'Kami berkomitmen merespons setiap pertanyaan dalam waktu <strong>5 hari kerja</strong>.',
    },
  },

  en: {
    topbarTitle: 'Privacy Policy',
    contents: 'Contents',
    title: 'Aviat Mobile Web Privacy Policy',
    effective: 'Effective: March 1, 2026',
    footerRights: 'All rights reserved.',

    sec1: {
      title: '1. General Information',
      p1: 'This Privacy Policy explains how <strong>Aviat Mobile Web</strong> ("App", "we", "us"), operated by <strong>RS Mitra Medika Premiere</strong>, collects, uses, stores, and protects your personal information when you use our services through a web browser on Android or iOS devices.',
      p2: 'This App is a digital healthcare platform serving two types of users: <strong>Patients</strong> — to book appointments, view visit history, lab results, prescription history, and vital sign charts; and <strong>Doctors</strong> — to manage appointment lists, inpatient lists, medical notes, and fee summaries.',
      note: '<strong>Important:</strong> By accessing or using this App, you acknowledge that you have read, understood, and agreed to all provisions of this Privacy Policy. If you do not agree, please discontinue use of the App.',
    },

    sec2: {
      title: '2. Data We Collect',
      intro: 'To deliver optimal digital healthcare services, we collect the following categories of data based on user type:',
      cols: ['Category', 'Type of Data', 'Collection Method'],
      rows: [
        ['Patient Identity', 'Full name, national ID (NIK), phone/WhatsApp number', 'Provided directly during registration'],
        ['Doctor Identity', 'Full name, phone/WhatsApp number, Doctor ID', 'Provided during registration and validated against SIMRS'],
        ['Patient Medical Data', 'Appointment history, visit history, lab results, prescription history, vital sign charts', 'Automatically from SIMRS system'],
        ['Notification Data', 'Device token for appointment notifications and reminders', 'Automatically generated upon login'],
      ],
    },

    sec3: {
      title: '3. Purpose of Data Use',
      intro: 'The data we collect is used solely for the following purposes:',
      items: [
        'Processing account registration and verifying user identity via WhatsApp OTP',
        'Enabling patients to create, view, and cancel appointments with doctors',
        'Displaying available doctor schedules including service units and operating hours',
        'Sending confirmation notifications, reminders, and appointment change or cancellation alerts',
        'Displaying patient visit history, lab results, prescription history, and vital sign charts',
        'Enabling doctors to access appointment lists, inpatient lists, medical notes, and fee summaries',
        'Synchronizing data with the Hospital Management Information System (SIMRS)',
        'Complying with legal and regulatory obligations in the healthcare sector',
        'Providing technical support to users',
      ],
      note: 'We do <strong>not use</strong> your data for third-party advertising, commercial profiling, or data sales in any form.',
    },

    sec4: {
      title: '4. Sharing Data with Third Parties',
      intro: 'We are committed to not selling or renting your personal data. Data may only be shared under the following circumstances:',
      cols: ['Recipient', 'Purpose', 'Basis'],
      rows: [
        ['Medical personnel & RS Mitra Medika Premiere staff', 'Delivering direct healthcare services, including access to appointment and medical record data', { label: 'Medical service', class: 'badge-medical' }],
        ['Technology service providers', 'Technical partners supporting app operations — bound by confidentiality agreements', { label: 'Contract & NDA', class: 'badge-contract' }],
        ['Government authorities', 'When required by applicable law or regulation (including Ministry of Health and BPJS Kesehatan)', { label: 'Legal obligation', class: 'badge-legal' }],
      ],
    },

    sec5: {
      title: '5. Data Security',
      intro: 'We implement technical and organizational measures in line with industry standards to protect your data, including:',
      cols: ['Security Measure', 'Description'],
      rows: [
        ['Data encryption in transit', 'All communications are encrypted via <strong>HTTPS/TLS</strong> through Cloudflare SSL'],
        ['Encryption at rest', 'Sensitive data is encrypted in storage using Laravel\'s built-in encryption features'],
        ['WhatsApp OTP authentication', 'Login uses a one-time code sent to your registered WhatsApp number — no password required'],
        ['CDN & firewall protection', 'Cloudflare is used as a CDN and firewall layer to prevent DDoS attacks and malicious access'],
        ['Security monitoring & audit logs', 'Regular security monitoring and system activity logging are conducted'],
        ['Security testing', 'Periodic application security testing is conducted'],
      ],
      note: 'No system is entirely immune to threats. If you suspect unauthorized access to your account, please contact us immediately.',
    },

    sec6: {
      title: '6. Data Retention & Deletion',
      intro: 'We retain your data for as long as necessary or as required by applicable law:',
      cols: ['Data Type', 'Retention Period', 'Basis'],
      rows: [
        ['Active account data', 'As long as account is active', 'Service requirement'],
        ['Medical records & clinical data', 'Minimum 5 years', 'Ministerial Regulation No. 24 of 2022'],
        ['Activity logs', 'Maximum 12 months', 'System security'],
        ['Transaction data', 'Minimum 5 years', 'Applicable tax regulations'],
      ],
      footer: 'Requests to delete your account may be submitted by contacting our team. Requests will be processed within <strong>30 business days</strong>.',
    },

    sec7: {
      title: '7. Your Rights',
      intro: 'Under the UU PDP and related regulations, you have the following rights:',
      cols: ['Right', 'Description'],
      rows: [
        ['Right of Access', 'Request a copy of personal data we hold about you'],
        ['Right to Rectification', 'Request correction of inaccurate or incomplete data'],
        ['Right to Erasure', 'Request deletion of your account and data, subject to legal retention obligations'],
        ['Right to Portability', 'Receive your data in a machine-readable format'],
        ['Right to Object', 'Object to specific processing activities'],
        ['Right to Withdraw Consent', 'Revoke any consent at any time by contacting our team or through your account settings'],
      ],
    },

    sec8: {
      title: '8. Children\'s Privacy Policy',
      p1: 'This App is not designed for independent use by children under the age of <strong>17</strong>. We do not knowingly collect personal data from minors without verifiable parental or guardian consent.',
      p2: 'Where patients are minors, registration and use of the App must be carried out by a parent or authorized guardian. If you believe a child\'s data has been collected without proper authorization, please contact us immediately.',
    },

    sec9: {
      title: '9. Changes to This Policy',
      intro: 'We may update this Privacy Policy from time to time. Material changes will be communicated through:',
      items: [
        'In-App notifications',
        'An in-App notice when you open the App after an update',
        'An updated "Effective" date at the top of this page',
      ],
      footer: 'Continued use of the App after the effective date of any changes constitutes your acceptance of the updated policy.',
    },

    sec10: {
      title: '10. Contact Us',
      intro: 'If you have any questions or requests regarding this Privacy Policy, please contact our team:',
      subtitle: 'Privacy & Digital Services Team',
      contacts: [
        { label: 'Address', value: 'Jl. Let. Jend. S. Parman No.236,<br>Medan, North Sumatra' },
        { label: 'Phone', value: '(061) – 4502021', href: 'tel:+62614502021' },
        { label: 'WhatsApp (24 Hours)', value: '+62 811-328-135', href: 'https://wa.me/628113281358' },
        { label: 'Emergency (24 Hours)', value: '+62 813-7618-7341', href: 'tel:+6281376187341' },
        { label: 'Website', value: 'mitramedika.com', href: 'https://mitramedika.com', target: '_blank' },
        { label: 'Instagram', value: '@rsmitramedikapremiere', href: 'https://instagram.com/rsmitramedikapremiere', target: '_blank' },
      ],
      responseNote: 'We are committed to responding to all inquiries within <strong>5 business days</strong>.',
    },
  },
}

const t = (key) => {
  const keys = key.split('.')
  let val = messages[lang.value]
  for (const k of keys) {
    val = val?.[k]
  }
  return val ?? key
}

// const navItems = [
//   { id: 'sec1',  label: { id: '1. Informasi Umum',       en: '1. General Information' } },
//   { id: 'sec2',  label: { id: '2. Data yang Dikumpulkan', en: '2. Data We Collect' } },
//   { id: 'sec3',  label: { id: '3. Tujuan Penggunaan',     en: '3. Purpose of Use' } },
//   { id: 'sec4',  label: { id: '4. Berbagi Data',          en: '4. Data Sharing' } },
//   { id: 'sec5',  label: { id: '5. Keamanan Data',         en: '5. Data Security' } },
//   { id: 'sec6',  label: { id: '6. Retensi Data',          en: '6. Data Retention' } },
//   { id: 'sec7',  label: { id: '7. Hak Pengguna',          en: '7. Your Rights' } },
//   { id: 'sec8',  label: { id: '8. Perlindungan Anak',     en: '8. Children\'s Policy' } },
//   { id: 'sec9',  label: { id: '9. Perubahan Kebijakan',   en: '9. Policy Changes' } },
//   { id: 'sec10', label: { id: '10. Hubungi Kami',         en: '10. Contact Us' } },
// ]

// ─── Intersection Observer ───────────────────────────────────────────────────
let observer = null

onMounted(() => {
  const sections = document.querySelectorAll('.pp-section[id]')
  observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) activeSection.value = entry.target.id
    })
  }, { rootMargin: '-20% 0px -70% 0px' })
  sections.forEach((s) => observer.observe(s))
})

onUnmounted(() => {
  observer?.disconnect()
})
</script>

<style scoped>
/* ─── RESET & BASE ─── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.pp-wrap {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  background: #fff;
  color: #1d1c1d;
  line-height: 1.7;
  font-size: 15px;
}

/* ─── OVERLAY ─── */
.pp-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.4);
  z-index: 98;
}
.pp-overlay.open { display: block; }

/* ─── TOPBAR ─── */
.pp-topbar {
  position: sticky;
  top: 0;
  z-index: 100;
  background: #fff;
  border-bottom: 1px solid #e8e8e8;
  padding: 0 16px;
  height: 56px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}
.pp-topbar-left {
  display: flex;
  align-items: center;
  gap: 10px;
  min-width: 0;
}
.pp-topbar-title {
  font-size: 17px;
  color: #616061;
  font-weight: 700;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.pp-hamburger {
  display: none;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border: 1px solid #e8e8e8;
  border-radius: 6px;
  background: none;
  cursor: pointer;
  flex-shrink: 0;
  padding: 0;
}
.pp-hamburger { display: flex; }
.pp-hamburger svg { width: 18px; height: 18px; }
.pp-lang-toggle {
  display: flex;
  border: 1px solid #ddd;
  border-radius: 6px;
  overflow: hidden;
  flex-shrink: 0;
}
.pp-lang-btn {
  padding: 5px 14px;
  font-size: 12px;
  font-weight: 500;
  font-family: inherit;
  cursor: pointer;
  border: none;
  background: transparent;
  color: #616061;
  transition: all .15s;
  letter-spacing: .02em;
}
.pp-lang-btn.active { background: #1d1c1d; color: #fff; }
.pp-lang-btn:not(.active):hover { background: #f4f4f4; }

/* ─── BODY ─── */
.pp-body {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 40px;
  display: flex;
  gap: 64px;
  align-items: flex-start;
}

/* ─── CONTENT ─── */
.pp-content { flex: 1; min-width: 0; padding: 40px 0 80px; }
.pp-content-header {
  border-bottom: 1px solid #e8e8e8;
  padding-bottom: 24px;
  margin-bottom: 40px;
  text-align: center;
}
.pp-content-header h1 {
  font-size: 28px;
  font-weight: 700;
  letter-spacing: -.02em;
  line-height: 1.25;
  color: #1d1c1d;
  margin-bottom: 8px;
}
.pp-effective { font-size: 13px; color: #616061; }

/* ─── SECTIONS ─── */
.pp-section { margin-bottom: 52px; scroll-margin-top: 72px; }
.pp-section h2 {
  font-size: 19px;
  font-weight: 700;
  color: #1d1c1d;
  letter-spacing: -.01em;
  margin-bottom: 14px;
  line-height: 1.3;
}
.pp-section p {
  font-size: 15px;
  color: #383838;
  margin-bottom: 12px;
  line-height: 1.7;
}
.pp-section p:last-child { margin-bottom: 0; }

/* ─── LIST ─── */
.pp-list { list-style: none; padding: 0; margin: 12px 0 16px; }
.pp-list li {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-size: 15px;
  color: #383838;
  padding: 5px 0;
  line-height: 1.6;
}
.pp-list li::before {
  content: '';
  width: 5px;
  height: 5px;
  border-radius: 50%;
  background: #1d1c1d;
  margin-top: 9px;
  flex-shrink: 0;
}

/* ─── NOTE ─── */
.pp-note {
  background: #f8f8f8;
  border-left: 3px solid #1d1c1d;
  padding: 14px 18px;
  margin: 16px 0;
  font-size: 14px;
  color: #383838;
  border-radius: 0 6px 6px 0;
  line-height: 1.6;
}

/* ─── TABLE ─── */
/*.pp-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
  margin: 14px 0; 
  table-layout: auto;
}

.pp-table th {
  text-align: left;
  padding: 10px 12px;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .05em;
  text-transform: uppercase;
  color: #616061;
  background: #f5f5f5;
  border-bottom: 1px solid #ddd;
}
.pp-table td {
  padding: 9px 8px;
  border-bottom: 1px solid #e8e8e8;
  color: #383838;
  vertical-align: top;
  line-height: 1.5;
  word-wrap: break-word;
} */

.pp-table tr:last-child td { border-bottom: none; }
.pp-table td strong { color: #1d1c1d; font-weight: 600; }
.pp-table tbody tr:hover td { background: #fafafa; }

.pp-table { font-size: 13px; table-layout: auto; }
.pp-table th, .pp-table td { padding: 9px 8px; }

/* ─── BADGES ─── */
.pp-badge {
  display: inline-block;
  font-size: 11px;
  padding: 3px 9px;
  border-radius: 999px;
  font-weight: 600;
  white-space: nowrap;
}
.badge-medical  { background: #dbeafe; color: #1e40af; }
.badge-contract { background: #dcfce7; color: #166534; }
.badge-legal    { background: #fef3c7; color: #92400e; }

/* ─── CONTACT ─── */
.pp-contact-card {
  border: 1px solid #e8e8e8;
  border-radius: 10px;
  padding: 24px 28px;
  margin-top: 20px;
}
.pp-contact-card h3 { font-size: 16px; font-weight: 700; color: #1d1c1d; margin-bottom: 4px; }
.pp-contact-subtitle { font-size: 13px; color: #888; margin-bottom: 20px; }
.pp-contact-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 16px 20px;
}
.pp-contact-row { display: flex; flex-direction: column; gap: 3px; }
.pp-c-label {
  font-size: 10px;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: #bbb;
}
.pp-c-val { font-size: 14px; color: #1d1c1d; font-weight: 500; }
.pp-c-val a {
  color: #1d1c1d;
  text-decoration: none;
  border-bottom: 1px solid #ddd;
  transition: border-color .15s;
}
.pp-c-val a:hover { border-color: #1d1c1d; }
.pp-contact-divider { height: 1px; background: #e8e8e8; margin: 20px 0; }
.pp-response-note { font-size: 13px; color: #888; }

/* ─── FOOTER ─── */
.pp-footer {
  border-top: 1px solid #e8e8e8;
  padding: 24px 40px;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}
.pp-footer p { font-size: 13px; color: #888; }
.pp-footer a { color: #888; text-decoration: none; }
.pp-footer a:hover { color: #1d1c1d; }
.pp-footer-brand { display: flex; align-items: center; gap: 8px; }
.pp-footer-logo {
  width: 26px; height: 26px;
  background: #1d1c1d;
  border-radius: 5px;
  display: flex; align-items: center; justify-content: center;
}
.pp-footer-logo svg { width: 14px; height: 14px; }
.pp-footer-brand span { font-size: 13px; font-weight: 600; color: #616061; }

/* ─── RESPONSIVE ─── */
@media (max-width: 900px) {
  .pp-body { gap: 40px; }
}

@media (max-width: 768px) {
  .pp-topbar { padding: 0 16px; height: 52px; }
  .pp-topbar-title { font-size: 15px; }
  .pp-hamburger { display: flex; }

  .pp-body { padding: 0; flex-direction: column; gap: 0; }

  .pp-content { padding: 24px 16px 60px; }
  .pp-content-header { margin-bottom: 28px; }
  .pp-content-header h1 { font-size: 20px; }

  .pp-section { margin-bottom: 36px; scroll-margin-top: 64px; }
  .pp-section h2 { font-size: 16px; }
  .pp-section p, .pp-list li { font-size: 14px; }

  .pp-table tr:last-child td { border-bottom: none; }
  .pp-table td strong { color: #1d1c1d; font-weight: 600; }
  .pp-table tbody tr:hover td { background: #fafafa; }
  .pp-table { font-size: 13px; table-layout: auto; }
  .pp-table th, .pp-table td { padding: 9px 8px; }

  .pp-contact-card { padding: 18px 16px; }
  .pp-contact-grid { grid-template-columns: 1fr 1fr; gap: 14px 12px; }

  .pp-footer { padding: 20px 16px; flex-direction: column; align-items: flex-start; }
}

@media (max-width: 480px) {
  .pp-col-hide { display: none; }
  .pp-contact-grid { grid-template-columns: 1fr; }
}
</style>