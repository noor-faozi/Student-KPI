# ![👩🏻_🎓Student_KPI (1)](https://github.com/user-attachments/assets/5771b8d8-882c-496d-be95-20ce81328959)

Student KPI is a web-based application designed to help students track their academic and extracurricular performance metrics effectively. The system provides a way to manage student KPIs based on various parameters such as academic performance, activities, competitions, and certifications.

## Built With
Frontend:
![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white) 
![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white) 
![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E)</br>
Backend: ![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)</br>
Database: ![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)</br>
Server: ![XAMPP](https://img.shields.io/badge/Xampp-F37623?style=for-the-badge&logo=xampp&logoColor=white)</br>

## How to Set Up
Follow these steps to set up and run the project on your local system:

1. Install Required Software
   Ensure you have the following installed:
   - XAMPP (Apache server and MySQL database)
   - A web browser
   - A code editor
   - GitHub Desktop (optional, if cloning the repository)

2. Clone the Repository
   If the repository is hosted on GitHub:
   Open GitHub Desktop or use a terminal.
   Clone the repository:
   `bash</br>
   git clone https://github.com/noor-faozi/Student-KPI.git`</br>
   Alternatively, download the ZIP file of the repository and extract it. 

3. Move Project Files to XAMPP
   Navigate to your XAMPP installation directory (usually `C:\xampp\htdocs on Windows` or `/opt/lampp/htdocs` on Linux).
   Copy the entire project folder (e.g., Student-KPI) into the htdocs directory.

4. Import the Database
   Start XAMPP and enable the Apache and MySQL services.
   Open a web browser and go to phpMyAdmin.
   Create a new database:
   Name it mykpi.
   Import the SQL file:
   Click Import.
   Select the .sql file from the project folder `(path: mysql/mykpi.sql)`.
   Click Go to execute the import.

6. Configure Database Connection
   Open the project files in a code editor.
   Locate the configuration file for database settings (config.php).
   Update the database credentials to match your setup:
   `$servername = "localhost";
   $username = "root";
   $password = ""; //Leave empty if no password
   $dbname = "mykpi";`</br>

8. Run the Application
   Open your web browser.
   Navigate to the project URL:
   http://localhost/Student-KPI/
   You should see the application homepage.

10. Default Login Credentials
   You can log in using the following credentials:</br>
   Username: 1234</br>
   Password: User@2024</br>

