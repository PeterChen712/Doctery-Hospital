# 🏥 Hospital Digitalization System Project Timeline (14 Days)

## 📋 Main Project:
**INDIVIDUAL PROJECT 3: Hospital Digitalization System**

### Overview:
A hospital digitalization system designed to manage hospital operations more efficiently through the roles of Admin, Doctor, and Patient. The system includes a comprehensive CMS module for managing users, medicines, and medical records, along with additional features like consultation scheduling and a feedback system to improve hospital service quality.

### User Levels:
1. **Admin**
   - Full access to manage all modules and users.
   - Responsible for hospital data settings, user management (Doctors and Patients), schedule management, and operational reports.
2. **Doctor**
   - Access and update patient medical records, provide diagnoses, and write prescriptions.
   - Can view and manage consultation schedules with patients.
3. **Patient**
   - Register and access hospital service information, such as consultation schedules and medical records.
   - Can view prescription status and treatment history.

### CMS Modules (Content Management System):
1. **User Management (Admin)**
   - **List Users:** Display all registered users (Admin, Doctor, and Patient).
   - **Create User:** Fields: Username, email, password, role (Admin, Doctor, Patient). Validation: All fields are required, and the user role must be correctly selected.
   - **Edit User:** Fields that can be changed: Username, email, password, role. Validation: Ensure no empty fields and data is in the correct format.
   - **Delete User:** Only Admin can delete user data.
2. **Medicine Management (Admin)**
   - **List Medicines:** Display available medicines with stock status.
   - **Create Medicine:** Fields: Medicine name, description, type (controlled/regular), stock, medicine image. Validation: All fields are required and the type must be selected.
   - **Edit Medicine:** Fields that can be changed: Medicine name, description, type, stock, medicine image. Validation: Ensure no empty fields and data is in the correct format.
   - **Delete Medicine:** Admin can delete medicines that are no longer used or out of stock.
3. **Medical Records Management (Doctor)**
   - **List Medical Records:** Display patient history and medical records.
   - **Create Medical Record:** Fields: Patient ID, Doctor ID, Medicine ID (can be more than one), medical action, treatment date. Validation: All data must be correctly filled for each patient.
   - **Edit Medical Record:** Fields that can be changed: Patient ID, Doctor ID, Medicine ID, action, treatment date. Validation: Ensure no empty fields.
   - **Delete Medical Record:** Doctors can only delete medical records they created themselves.

### Layout Requirements:
1. **Login/Register Page**
   - Login for Admin, Doctor, and Patient.
   - Register only for Patient accounts.
2. **Dashboard**
   - **Admin:** Display the list of doctors on duty that day. Show total users and their roles.
   - **Doctor:** Display the 5 most recently examined patients. Quick access to ongoing medical records.
   - **Patient:** Display medical actions and medicines prescribed by the doctor. Automatic notifications for follow-up consultations or medicine pickups.
3. **User List Page**
   - **Admin:** Display all users with role and registration date filters.
   - **Doctor:** List of patients treated with search options.
4. **Medicine List Page**
   - **Admin:** Display all medicines with stock status. Filters: Available, unavailable, or expired medicines.
5. **Medical Records Page**
   - **Doctor:** Access all patient medical history by name or date.
   - **Patient:** Can only view their own medical records.
6. **Profile Management Page**
   - Display complete user data based on their role. Doctors and Patients can update their personal information.

### Advanced Requirements (Optional Upgrades):
1. **Appointment Scheduling System**
   - Patients can view doctor schedules and book consultation slots.
2. **Feedback System**
   - Reviews and ratings from patients.
3. **Filter and Sort Doctor Names for Admin**
   - Add search options for doctors by name.
4. **Filter and Sort Medicine Names**
   - Add search options for medicines by name.

## 🗓️ 7-Day Timeline: Hospital Digitalization System
**Day 1: Project Setup & Authentication**
- Setup new Laravel project
- Install and configure Laravel Breeze for basic authentication
- Install Spatie Permission for role management (Admin, Doctor, Patient)
- **Database Setup:**
  - Configure migrations for users, roles, and permissions
  - Seed initial roles (Admin, Doctor, Patient) and a default Admin user

**Day 2: User Management (Admin)**
- Admin CRUD for managing users (Admin, Doctor, Patient):
  - List Users: Display all users with filters for roles and registration date
  - Create User: Username, email, password, role (validation for required fields)
  - Edit User: Update username, email, password, role with validation
  - Delete User: Only Admin has access
- Role-based Middleware: Ensure that only Admins can access user management features

**Day 3: Medicine Management (Admin)**
- Medicine CRUD for Admin:
  - List Medicines: Show all available medicines with stock status
  - Create Medicine: Fields - name, description, type (regular/controlled), stock, and image upload (validation for required fields)
  - Edit Medicine: Update all medicine details, ensuring data validity
  - Delete Medicine: Only Admin can delete medicines
- Filtering & Search: Basic filtering for available/out-of-stock/expired medicines

**Day 4: Medical Records Management (Doctor)**
- Medical Records CRUD for Doctor:
  - List Medical Records: Show patient history and medical records
  - Create Medical Record: Fields - Patient ID, Doctor ID, Medicine ID(s), treatment, date
  - Edit Medical Record: Update patient details, treatment, and date with validation
  - Delete Medical Record: Only the Doctor who created the record can delete it
- Relationship Setup: Define relationships between User, MedicalRecord, and Medicine models

**Day 5: Dashboard Layouts & Profile Management**
- Role-Based Dashboards:
  - Admin Dashboard: Show active doctors for the day, user statistics by role
  - Doctor Dashboard: List of recently examined patients, quick access to current medical records
  - Patient Dashboard: Display treatments received and medications prescribed
- Profile Management: Allow Doctor and Patient to update their personal information

**Day 6: UI & Validation**
- UI Enhancements:
  - Refine dashboard layouts using Tailwind and minimal components for clarity
  - Implement responsive design for optimal viewing on multiple devices
- Data Validation:
  - Add validation rules across all forms to prevent incomplete/invalid submissions
  - Error handling: Display user-friendly messages for form validation errors

**Day 7: Final Testing & Documentation**
- System Testing:
  - Verify role-based access for each module and CRUD operation
  - Test all dashboards and profile update functionality
- Documentation:
  - Document setup instructions, user roles, and core CMS functionality
  - Finalize README for deployment
- Final Bug Fixes: Address any remaining bugs found during testing

## 📊 Database Schema:

erDiagram
    USERS ||--o{ MEDICAL_RECORDS : "creates/has"
    USERS ||--o{ APPOINTMENTS : "schedules/has"
    USERS ||--o{ FEEDBACK : "gives"
    USERS ||--o{ USER_NOTIFICATIONS : "receives"
    DOCTORS }|--|| USERS : "is"
    PATIENTS }|--|| USERS : "is"
    ADMINS }|--|| USERS : "is"
    MEDICINES ||--o{ MEDICAL_RECORDS : "prescribed in"
    MEDICAL_RECORDS ||--o{ PRESCRIPTIONS : "has"
    MEDICINES ||--o{ PRESCRIPTIONS : "contains"
    DOCTORS ||--o{ DOCTOR_SCHEDULES : "has"
    DOCTOR_SCHEDULES ||--o{ APPOINTMENTS : "available for"

    USERS {
        int user_id PK
        string username
        string email
        string password
        enum role "ADMIN/DOCTOR/PATIENT"
        string profile_image
        string phone_number
        string address
        boolean is_active
        datetime last_login
        datetime created_at
        datetime updated_at
    }

    DOCTORS {
        int doctor_id PK
        int user_id FK
        string specialization
        string license_number
        text education
        text experience
        decimal consultation_fee
        boolean is_available
        datetime created_at
        datetime updated_at
    }

    PATIENTS {
        int patient_id PK
        int user_id FK
        date date_of_birth
        string blood_type
        text allergies
        text medical_history
        string emergency_contact
        datetime created_at
        datetime updated_at
    }

    ADMINS {
        int admin_id PK
        int user_id FK
        string department
        int access_level
        string position
        datetime created_at
        datetime updated_at
    }

    MEDICINES {
        int medicine_id PK
        string name
        string description
        enum type "KERAS/BIASA"
        decimal price
        int stock
        string image_url
        boolean is_available
        date expiry_date
        string manufacturer
        string category
        datetime created_at
        datetime updated_at
    }

    MEDICAL_RECORDS {
        int record_id PK
        int patient_id FK
        int doctor_id FK
        text symptoms
        text diagnosis
        text medical_action
        text lab_results
        datetime treatment_date
        text notes
        enum status
        datetime follow_up_date
        datetime created_at
        datetime updated_at
    }

    PRESCRIPTIONS {
        int prescription_id PK
        int record_id FK
        int medicine_id FK
        int quantity
        text dosage
        text instructions
        enum status "PENDING/PROCESSED/COMPLETED"
        datetime valid_until
        datetime created_at
        datetime updated_at
    }

    APPOINTMENTS {
        int appointment_id PK
        int patient_id FK
        int doctor_id FK
        int schedule_id FK
        datetime appointment_date
        enum status "PENDING/CONFIRMED/CANCELLED/COMPLETED"
        text reason
        text notes
        boolean is_rescheduled
        datetime created_at
        datetime updated_at
    }

    DOCTOR_SCHEDULES {
        int schedule_id PK
        int doctor_id FK
        date schedule_date
        time start_time
        time end_time
        int max_appointments
        boolean is_available
        enum day_of_week
        datetime created_at
        datetime updated_at
    }

    FEEDBACK {
        int feedback_id PK
        int patient_id FK
        int doctor_id FK
        int appointment_id FK
        int rating
        text review
        text doctor_response
        boolean is_public
        datetime created_at
        datetime updated_at
    }

    USER_NOTIFICATIONS {
        int notification_id PK
        int user_id FK
        string title
        text message
        enum type "APPOINTMENT/PRESCRIPTION/GENERAL"
        boolean is_read
        datetime created_at
    }