# Project Title: Laravel Dating Application

## Description
This is a comprehensive dating application built with Laravel, designed to provide a modern and engaging user experience. The application includes features such as user profile management, a discovery system for finding potential matches, real-time chat, and a subscription-based model for premium features.

## Key Features
- **User Profile Management:** Users can create and edit their profiles, upload photos, and set a primary photo.
- **Discovery System:** A system for users to discover and like other users.
- **Matching:** When two users like each other, a match is created, allowing them to start a conversation.
- **Real-Time Chat:** A real-time chat system for matched users to communicate.
- **Reporting System:** Users can report messages, conversations, or other users for moderation.
- **Subscription Plans:** The application includes a subscription model with different plans, managed through MercadoPago.
- **Admin Panel:** A comprehensive admin panel for moderation, user management, and viewing reports.

## Tech Stack
- **Backend:** Laravel, PHP
- **Database:** SQLite
- **Frontend:** Tailwind CSS, Vite.js
- **Payment Gateway:** MercadoPago

## Installation and Setup
1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/your-repository.git
   cd your-repository
   ```

2. **Run the setup script:**
   ```bash
   composer run setup
   ```

3. **Start the development server:**
   ```bash
   composer run dev
   ```

## Usage
After installation, you can access the application in your web browser. The `dev` script starts the Laravel development server, a queue listener, and Vite for asset bundling.

## Contributing
Contributions are welcome! If you'd like to contribute to the project, please follow these steps:
1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Make your changes and commit them.
4. Push your changes to your fork.
5. Open a pull request.

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
