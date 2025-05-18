# 🛒 Filament Smart Bazar

**Filament Smart Bazar** is a dynamic, admin-based food management system built with Laravel and Filament. It helps efficiently manage and track prices of common Bangladeshi food items. The system integrates AI to suggest market-relevant pricing and units, reducing guesswork and manual data entry.

---

## ✅ Key Features

- **AI-Enhanced Price Suggestion**  
  When users input a food name, the system uses OpenAI to suggest current market prices and appropriate units (e.g., কেজি, টি).

- **Price Tracking with History**  
  Price changes are automatically logged, and users can view a visual history chart for each item.

- **Easy Item Management**  
  Add, edit, or update food items in Bangla using a clean, intuitive admin interface built with Filament.

- **One-Click Refresh Option**  
  Instantly request updated AI suggestions for price and unit with a dedicated refresh button.

- **Interactive Admin Dashboard**  
  Built with Filament 3, Livewire, and Tailwind — optimized for performance and usability.

---

## 🧱 Technology Stack

- Laravel 10+
- Filament 3 (Admin Panel)
- OpenAI API (for AI suggestions)
- Livewire + AlpineJS
- Tailwind CSS

---

## ⚙️ Installation Guide

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourname/filament-smart-bazar.git
   cd filament-smart-bazar
````

2. **Install dependencies**

   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment configuration**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Add your OpenAI API key:

   ```
   OPENAI_API_KEY=your_openai_api_key_here
   ```

4. **Run the database migrations**

   ```bash
   php artisan migrate
   ```

5. **Create an admin user**

   ```bash
   php artisan tinker
   >>> \App\Models\User::create([
         'name' => 'Admin',
         'email' => 'admin@example.com',
         'password' => bcrypt('password'),
     ]);
   ```

6. **Access the dashboard**
   Visit `/admin` in your browser and log in.

---

## 📬 Contact

**Najmul Hasan**
📧 [najmulhasansobuj87@gmail.com](mailto:najmulhasansobuj87@gmail.com)

---

## 📄 License

MIT License. Free to use and modify with proper credit.

```

---

### 🧠 Summary

| Purpose          | README Style                     |
|------------------|----------------------------------|
| Public repo       | Friendly, fun, open-source vibe |
| Client/project delivery | Formal, benefit-focused, clean |

Let me know if you want a **pitch deck-style PDF version** or a **landing page layout** based on this!
```
