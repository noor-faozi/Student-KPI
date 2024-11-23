### Student KPI

Student KPI is a web-based application designed to help students track their academic and extracurricular performance metrics effectively. The system provides a way to manage student KPIs based on various parameters such as academic performance, activities, competitions, and certifications.

Technologies Used
Frontend: HTML, CSS, JavaScript
Backend: PHP
Database: MySQL
Server: XAMPP (for local development)
Setup Instructions
Follow these steps to set up and run the project on your local system:

1. Install Required Software
   Ensure you have the following installed:

XAMPP (Apache server and MySQL database)
A web browser
A code editor
GitHub Desktop (optional, if cloning the repository)

2. Clone the Repository
   If the repository is hosted on GitHub:

Open GitHub Desktop or use a terminal.
Clone the repository:
bash
Copy code
git clone https://github.com/noor-faozi/Student-KPI.git
Alternatively, download the ZIP file of the repository and extract it. 3. Move Project Files to XAMPP
Navigate to your XAMPP installation directory (usually C:\xampp\htdocs on Windows or /opt/lampp/htdocs on Linux).
Copy the entire project folder (Student-KPI) into the htdocs directory. 4. Import the Database
Start XAMPP and enable the Apache and MySQL services.
Open a web browser and go to phpMyAdmin.
Create a new database:
Name it "mykpi".
Import the SQL file:
Click Import.
Select the .sql file from the project folder (path: mysql/mykpi.sql).
Click Go to execute the import. 5. Configure Database Connection
Open the project files in a code editor.
Locate the configuration file for database settings (config.php).
Update the database credentials to match this setup:
$servername = "localhost";
$username = "root";
$password = ""; // Leave empty if no password
$dbname = "mykpi"; 6. Run the Application
Open your web browser.
Navigate to the project URL:
arduino
http://localhost/Student-KPI/
You should see the application homepage. 7. Default Login Credentials
You can log in using the following credentials:

Username: 1234
Password: User@2024
