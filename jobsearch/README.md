# Job Search Application

## Overview
This project is a Job Search Application that allows users to post job listings, view applicants, manage job locations, and handle employer profiles. It provides a user-friendly interface for both employers and job seekers.

## Features
- **Post Job Listings**: Employers can create and manage job postings.
- **View Applicants**: Employers can view a list of applicants for their job postings.
- **Job Location Management**: Manage job locations for postings.
- **Employer Profiles**: Employers can create and manage their profiles.
- **Interview Scheduling**: Schedule interviews with applicants.
- **User Complaints**: Handle user complaints and feedback.

## File Structure
```
jobsearch
├── assets
│   ├── main_nav.css       # CSS styles for the main navigation bar
│   └── sidebar.css        # CSS styles for the sidebar navigation
├── dashboards
│   ├── company_dashboard.php  # Form for posting job listings
│   ├── view_applicants.php    # Displays a list of applicants
│   ├── post_job_locations.php  # Manages job locations
│   ├── views.php              # Displays job viewer statistics
│   ├── post_employer_profiles.php # Manages employer profiles
│   ├── interview.php          # Manages interview scheduling
│   └── complain.php           # Handles user complaints
├── includes
│   └── db.php                # Database connection logic
├── templates
│   └── pwk.png               # Logo for the application
├── index.php                 # Main entry point of the application
├── login.php                 # User login functionality
├── logout.php                # User logout functionality
└── README.md                 # Project documentation
```

## Setup Instructions
1. Clone the repository to your local machine.
2. Navigate to the project directory.
3. Set up your database and update the `includes/db.php` file with your database credentials.
4. Run the application on a local server (e.g., XAMPP, WAMP).
5. Access the application via your web browser.

## Usage
- Navigate to `index.php` to view the homepage.
- Use the navigation bar to access different features of the application.
- Follow the prompts to post jobs, view applicants, and manage profiles.

## Contributing
Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.