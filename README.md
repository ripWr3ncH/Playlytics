# Playlytics - Football Match Analytics & MySQL Query Showcase

A comprehensive web-based football/soccer statistics management system demonstrating MySQL database concepts with a focus on SQL queries. Built with HTML, CSS, JavaScript, and PHP.

## 🎯 Project Overview

This project showcases various MySQL database concepts including:
- Complex SQL queries with multiple JOINs
- Aggregate functions (COUNT, SUM, AVG, MIN, MAX)
- Subqueries and nested queries
- GROUP BY with HAVING clauses
- UNION operations
- CASE statements for conditional logic
- Database relationships (One-to-Many, Many-to-One)
- CRUD operations (Create, Read, Update, Delete)
- Real-time SQL query viewer

## ✨ Key Features

### Public Features
- **Home Dashboard**: Live matches, recent results, and upcoming fixtures
- **Leagues Page**: League listings with detailed standings calculations
- **Players Page**: Player statistics, top scorers, and team analysis
- **SQL Query Executor**: Interactive SQL editor to write and execute custom queries
- **Real-time SQL Viewer**: Modal dialog showing all SQL queries executed on each page

### Admin Panel
- **Dashboard**: Comprehensive statistics and quick actions
- **League Management**: Full CRUD operations for leagues
- **Team Management**: Create, edit, and delete teams
- **Player Management**: Manage player information and assignments
- **Match Management**: Schedule and update match results

## 📋 Requirements

- **XAMPP** (Apache + MySQL + PHP)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Modern web browser

## 🚀 Installation Instructions

### 1. Start XAMPP
1. Open XAMPP Control Panel
2. Start **Apache** and **MySQL** services

### 2. Setup Database
1. Open your browser and navigate to:
   ```
   http://localhost/Playlytics/setup/install.php
   ```
2. This will:
   - Create the database `playlytics_db`
   - Create all necessary tables
   - Create an admin user

### 3. Seed Dummy Data
1. Navigate to:
   ```
   http://localhost/Playlytics/setup/seed_data.php
   ```
2. This will populate the database with:
   - 4 football leagues (Premier League, La Liga, Bundesliga, Serie A)
   - 16 teams across all leagues
   - 30+ players with real names
   - 14 matches (live, finished, and scheduled)
   - Player statistics for matches

### 4. Access the Application
- **Main Site**: `http://localhost/Playlytics/`
- **Admin Panel**: `http://localhost/Playlytics/admin/`
- **SQL Executor**: `http://localhost/Playlytics/pages/query_executor.php`

## 🗂️ Project Structure

```
Playlytics/
├── admin/
│   ├── index.php                 # Admin dashboard
│   ├── manage_leagues.php        # League CRUD
│   ├── manage_teams.php          # Team CRUD
│   ├── manage_players.php        # Player CRUD
│   └── manage_matches.php        # Match CRUD
├── assets/
│   ├── css/
│   │   └── style.css            # Main stylesheet
│   └── js/
│       └── main.js              # JavaScript functions
├── config/
│   ├── config.php               # Configuration settings
│   └── db_connect.php           # Database connection
├── includes/
│   ├── header.php               # Header with SQL viewer modal
│   └── footer.php               # Footer template
├── pages/
│   ├── leagues.php              # Leagues listing & standings
│   ├── players.php              # Players listing & statistics
│   └── query_executor.php       # SQL query executor
├── reference/
│   ├── create_table.sql         # Database schema
│   ├── all_sqls_list.txt        # SQL commands reference
│   └── PROJECT_REFERENCE.txt    # Project documentation
├── setup/
│   ├── install.php              # Database installation
│   └── seed_data.php            # Dummy data seeder
├── index.php                     # Home page
└── README.md                     # This file
```

## 💾 Database Schema

### Tables
1. **users** - User accounts and admin access
2. **leagues** - Football leagues information
3. **teams** - Teams belonging to leagues
4. **players** - Player information linked to teams
5. **football_matches** - Match records with scores and status
6. **player_stats** - Player performance per match

### Relationships
- League → Teams (One-to-Many)
- Team → Players (One-to-Many)
- League → Matches (One-to-Many)
- Match → Player Stats (One-to-Many)
- Team → Matches (One-to-Many for both home and away)

## 🔍 SQL Queries Demonstrated

### Basic Operations
- SELECT with WHERE, ORDER BY, LIMIT
- INSERT statements
- UPDATE statements
- DELETE statements

### Advanced Queries
- INNER JOIN (multiple tables)
- LEFT JOIN with NULL handling
- Aggregate functions (COUNT, SUM, AVG)
- GROUP BY with HAVING
- Subqueries in SELECT, FROM, WHERE
- UNION operations
- CASE statements for conditional logic
- Complex standings calculations

### Example Queries Available
1. **Basic SELECT**: Fetch all teams
2. **Multiple JOINs**: Get matches with team names
3. **Aggregation**: Count players per team
4. **Subqueries**: Find teams with most players
5. **UNION**: Combine teams and players
6. **Complex**: League statistics with averages

## 🎨 Features Highlight

### 1. SQL Query Viewer Modal
- Click "📊 View SQL Queries" button in navigation
- Shows all SQL queries executed on the current page
- Displays query description and execution time
- Helps understand database operations

### 2. Interactive SQL Executor
- Write custom SQL queries
- Execute SELECT, INSERT, UPDATE statements
- View results in formatted tables
- Sample queries provided for learning
- Safety checks for dangerous operations

### 3. Real-time Match Tracking
- Live match indicators with pulsing animation
- Auto-refresh capability for live scores
- Match status badges (Live, Finished, Scheduled)

### 4. League Standings Calculator
- Complex SQL query calculating:
  - Matches played
  - Wins, draws, losses
  - Goals for and against
  - Goal difference
  - Points (3 for win, 1 for draw)

### 5. Player Statistics
- Total goals and assists
- Yellow and red cards
- Matches played
- Top scorers leaderboard

## 🎓 Educational Value

This project demonstrates:
- Database design and normalization
- Foreign key relationships and cascading
- Complex SQL query construction
- JOIN operations between multiple tables
- Aggregate functions and grouping
- Subquery techniques
- CRUD operations
- Server-side validation
- Responsive web design
- JavaScript DOM manipulation

## 🛠️ Customization

### Adding Sample Queries
Edit `pages/query_executor.php` to add more sample queries in the sample queries section.

### Modifying Styles
Edit `assets/css/style.css` to customize colors, fonts, and layouts.

### Adding More Data
Run additional INSERT queries via the SQL Executor or phpMyAdmin.

## 📝 SQL Commands Reference

The project demonstrates all SQL commands from `all_sqls_list.txt`:
- ✅ CREATE TABLE
- ✅ ALTER TABLE (ADD, MODIFY, RENAME, DROP)
- ✅ INSERT INTO
- ✅ SELECT (with WHERE, JOIN, GROUP BY, HAVING, ORDER BY)
- ✅ UPDATE
- ✅ DELETE
- ✅ FOREIGN KEY constraints
- ✅ ON DELETE CASCADE
- ✅ UNIQUE, NOT NULL, CHECK constraints
- ✅ DEFAULT values
- ✅ DISTINCT, BETWEEN, IN, LIKE
- ✅ Aggregate functions (COUNT, SUM, AVG, MIN, MAX)
- ✅ Subqueries
- ✅ UNION operations
- ✅ Multiple JOIN types

## 🔐 Default Credentials

**Admin Access:**
- Email: admin@playlytics.com
- Password: admin123

*Note: Change these in production!*

## 🐛 Troubleshooting

### Database Connection Error
- Ensure MySQL is running in XAMPP
- Check credentials in `config/config.php`
- Verify database name is correct

### No Data Showing
- Run `setup/seed_data.php` to populate database
- Check if tables were created via phpMyAdmin

### SQL Queries Not Showing in Viewer
- Ensure you've included both header and footer files
- Check if `getQueryLog()` function is available

## 🎯 Use Cases

1. **Database Learning**: Understand SQL concepts visually
2. **Portfolio Project**: Showcase full-stack development skills
3. **Sports Analytics**: Track match results and player performance
4. **Educational Tool**: Teach SQL to students
5. **Template**: Base for similar data-driven applications

## 📚 Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Design**: Custom CSS (No frameworks)
- **Server**: Apache (XAMPP)

## 🤝 Contributing

This is an educational project. Feel free to:
- Add more SQL query examples
- Enhance the UI/UX
- Add new features
- Improve documentation

## 📄 License

This project is created for educational purposes.

## 👨‍💻 Author

Created as a MySQL query showcase project demonstrating database concepts and relationships.

---

**Enjoy exploring SQL queries with Playlytics! ⚽📊**
