# REVISION LOGS

| Date           | Author     | Description   |
| -------------- | ---------- | ------------- |
| 25 August 2023 | Raja Azian | Initial draft |

# WORKS ITEMS

Phase 1 (2808-0409)

-   #1 Login page (Pasien Dan Dokter)
-   #2 Register page (Pasien Dan Dokter)
-   #3 OTP Page (Pasien Dan Dokter)
-   #4 Konfirmasi Data Page (Pasien Dan Dokter)
-   #5 Homepage (Pasien Dan Dokter)
-   #6 Profile Page (Pasien)
-   #7 Edit Profile Page (Pasien)

Phase 2 (0409-1109)

-   #8 Family Member Page (Pasien)
-   #9 Edit Family Member (Pasien)
-   #10 Patient Vital Sign history (Pasien)
-   #11 Lab Result History Page (Pasien)
-   #12 Lab Result History Detail Page (Pasien)
-   #13 List Encounter History Page (Pasien)
-   #14 List Encounter History Detail Page (Pasien)

Phase 3 (1109-1809)

-   #15 Detail Jadwal Dokter Page (Pasien)
-   #16 List Prescription History Page (Pasien)
-   #17 List Prescription History Detail Page (Pasien)
-   #18 List Appointment Page (Pasien)
-   #19 Form Appointment Page (Pasien)
-   #20 Edit Profile Page (Dokter)
-   #21 Profile Page (Dokter)

Phase 4 (1809-2509)

-   #22 Summary Fee Page (Dokter)
-   #23 Inpatient List Page (Dokter)
-   #24 Inpatient Detail Page (Dokter)
-   #25 Appointment List Page (Dokter)
-   #26 Appointment List Detail Page (Dokter)

# CONFIDENTIALITY

The information contained in these document is confidential, privileged, and only for information of intended receipent and may not be used, published, or restributed without the prior consent.

# SUMMARY

This document serves as a design/technical draft outlining a proposal for implementing Doctor-Patient Appoitment System.

# ASSUPMTIONS, RISKS, AND DEPEDENCIES

## Technologies

| Name      | Type                  | Role                                                                                            | Notes |
|-----------|-----------------------|-------------------------------------------------------------------------------------------------|-------|
| Laravel   | Fullstack framework   | The main development framework for the portal. Act as define, store, & manipulating portal data |       |
| Postgres  | Relational Database   | Data persistence                                                                                |       |
| Vue.js    | Front-end library     | Provide reactive DOM manipulation                                                               |       |
| Bootstrap | Front-end CSS library | Provide predefine HTML-CSS components                                                           |       |

# FUNCTIONAL REQUIREMENTS

## Phase 1 (2808-0409)

### 1. Login Page

-   Implement a login page with fields for username and password for both patients and doctors.
-   Authenticate users against the stored credentials.
-   Provide error messages for invalid login attempts.

### 2. Register Page

-   Create a registration page allowing patients and doctors to sign up.
-   Collect necessary information including username, password, email, and other relevant details.
-   Validate input data and handle registration errors.

### 3. OTP Page

-   Develop an OTP (One-Time Password) page for additional security during registration and login.
-   Generate and send OTP to the user's email or phone number.
-   Allow users to input and verify the OTP.

### 4. Konfirmasi Data Page

-   Create a page where users confirm their registration details before finalizing the registration process.
-   Display entered information and allow users to make corrections if needed.

### 5. Homepage

-   Design a homepage for both patients and doctors after successful login.
-   Include relevant information, shortcuts, and announcements.

### 6. Profile Page (Pasien)

-   Implement a patient profile page displaying personal information and medical history.
-   Allow patients to view and update their profile details.

### 7. Edit Profile Page (Pasien)

-   Create an edit profile page for patients to modify their personal information.
-   Validate and save changes to the profile.

## Phase 2 (0409-1109)

### 1. Family Member Page (Pasien)

-   Develop a page where patients can manage their family members' information.
-   Allow adding, editing, and deleting family member details.

### 2. Edit Family Member (Pasien)

-   Enable patients to edit the details of their existing family members.
-   Validate and save the changes.

### 3. Patient Vital Sign History (Pasien)

-   Implement a page displaying the patient's historical vital sign data.
-   Display a time-series graph and relevant statistics.

### 4. Lab Result History Page (Pasien)

-   Create a page to show the patient's historical lab results.
-   Display results in a structured format with necessary details.

### 5. Lab Result History Detail Page (Pasien)

-   Allow patients to view detailed information about specific lab results.

### 6. List Encounter History Page (Pasien)

-   Develop a page showing the patient's history of medical encounters.
-   Display key information and allow users to access more details.

### 7. List Encounter History Detail Page (Pasien)

-   Provide a detailed view of a specific medical encounter from the history.

## Phase 3 (1109-1809)

### 1. Detail Jadwal Dokter Page (Pasien)

-   Implement a page where patients can view detailed doctor schedules.
-   Show available time slots and appointment booking options.

### 2. List Prescription History Page (Pasien)

-   Create a page displaying the patient's prescription history.
-   Include details about medications, dosages, and prescribing doctors.

### 3. List Prescription History Detail Page (Pasien)

-   Allow patients to view detailed information about specific prescriptions.

### 4. List Appointment Page (Pasien)

-   Develop a page for patients to view their upcoming and past appointments.

### 5. Form Appointment Page (Pasien)

-   Create a page for patients to schedule appointments with doctors.
-   Allow selection of preferred date and time.

### 6. Edit Profile Page (Dokter)

-   Develop an edit profile page for doctors to update their personal and professional information.

### 7. Profile Page (Dokter)

-   Implement a profile page for doctors to view their details and patient-related information.

## Phase 4 (1809-2509)

### 1. Summary Fee Page (Dokter)

-   Develop a page for doctors to review their earnings and fees for services provided.

### 2. Inpatient List Page (Dokter)

-   Create a page displaying a list of inpatients under the doctor's care.
-   Include relevant details about their condition and treatment.

### 3. Inpatient Detail Page (Dokter)

-   Allow doctors to access detailed information about a specific inpatient.

### 4. Appointment List Page (Dokter)

-   Implement a page displaying the doctor's upcoming and past appointments.

### 5. Appointment List Detail Page (Dokter)

-   Provide a detailed view of a specific appointment, including patient information and purpose of visit.

## SCHEMAS

_blank section_

## Entities

_blank section_

# COST CONSIDERATIONS

_blank section_
