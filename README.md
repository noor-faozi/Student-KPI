# ![👩🏻_🎓Student_KPI (1)](https://github.com/user-attachments/assets/5771b8d8-882c-496d-be95-20ce81328959)

Student KPI is a web-based application designed to help students track their academic and extracurricular performance metrics effectively. The system provides a way to manage student KPIs based on various parameters such as academic performance, activities, competitions, and certifications.
![homepage](https://github.com/user-attachments/assets/c83cb2fa-6d81-45eb-9688-ea04e1e539bf)</br>
![loginpage](https://github.com/user-attachments/assets/3347cd41-ed63-4853-b21d-2787fe5ea25f)


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

2. Clone the Repository</br>
   If the repository is hosted on GitHub:</br>
   Open GitHub Desktop or use a terminal.</br>
   Clone the repository:</br>
   ```bash
   git clone https://github.com/noor-faozi/Student-KPI.git
</br>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alternatively, download the ZIP file of the repository and extract it. 

3. Move Project Files to XAMPP
   Navigate to your XAMPP installation directory (usually `C:\xampp\htdocs on Windows` or `/opt/lampp/htdocs` on Linux).</br>
   Copy the entire project folder (e.g., Student-KPI) into the htdocs directory.

4. Import the Database</br>
   Start XAMPP and enable the Apache and MySQL services.</br>
   Open a web browser and go to phpMyAdmin.</br>
   Create a new database:</br>
   Name it "mykpi".</br>
   Import the SQL file:</br>
   Click Import.</br>
   Select the .sql file from the project folder `(path: mysql/mykpi.sql)`.</br>
   Click Go to execute the import.</br>

6. Configure Database Connection
   Open the project files in a code editor.</br>
   Locate the configuration file for database settings (config.php).</br>
   Update the database credentials to match your setup:</br>
   ```php
   $servername = "localhost";
   $username = "root";
   $password = ""; //Leave empty if no password
   $dbname = "mykpi";


8. Run the Application
   Open your web browser.</br>
   Navigate to the project URL:</br>
   http://localhost/Student-KPI/</br>
   You should see the application homepage.

10. Default Login Credentials
   You can log in using the following credentials:</br>
   Username: 1234</br>
   Password: User@2024</br>

