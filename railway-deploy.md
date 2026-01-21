# Railway Deployment Complete Guide - Laravel Project

## 🚀 Complete Railway Deployment Process (Start to End)

### Prerequisites:
- GitHub account
- Railway account ([railway.app](https://railway.app))
- Laravel project ready

---

## Step 1: Prepare Laravel Project for Railway

### 1.1 Update Project Files
Your project already has these files configured:
- ✅ `nixpacks.toml` - Railway build configuration
- ✅ `Procfile` - Web server configuration
- ✅ `.env.example` - Environment template
- ✅ `app-layout.blade.php` - Missing component fixed

### 1.2 Generate APP_KEY (Local)
```bash
cd Stitching-Erp
php artisan key:generate --show
```
**Copy this key** - you'll need it later: `base64:eZPPIOxFSMhB6HEMUlB8UVUMVv+3d91dtqAZEWP89fU=`

---

## Step 2: Deploy Code to Railway

### 2.1 Create Railway Account
1. Go to [railway.app](https://railway.app)
2. Sign up with GitHub account

### 2.2 Deploy Laravel Project
1. Click **"New Project"**
2. Select **"Deploy from GitHub repo"**
3. Choose your **Stitching-Erp** repository
4. Railway will automatically detect Laravel and start building

### 2.3 Wait for Initial Build
- Build will take 2-3 minutes
- Don't worry if it fails initially - we'll fix it

---

## Step 3: Add MySQL Database Service

### 3.1 Add Database to Project
1. In your Railway project dashboard
2. Click **"New Service"** or **"+"** button
3. Select **"Database"**
4. Choose **"Add MySQL"**
5. Railway will create MySQL service automatically

### 3.2 Get Database Connection Details
1. Click on **MySQL service** (not your app)
2. Go to **"Connect"** tab
3. Select **"Public Network"** tab
4. Copy these details:
   - **Host**: `caboose.proxy.rlwy.net` (example)
   - **Port**: `16447` (example)
   - **Database**: `railway`
   - **Username**: `root`
   - **Password**: `[generated password]`

---

## Step 4: Connect App Service to Database (Updated Method)

### 4.1 Method 1: Shared Variables (Railway New Feature)

**Automatic Connection:**
1. Go to your **App Service** (Laravel project)
2. Click **"Variables"** tab
3. Railway automatically detects MySQL service in same project
4. Click **"Reference"** or **"Add Reference"**
5. Select your **MySQL service**
6. Railway will automatically add these variables:
   ```
   DATABASE_URL=${{MySQL.DATABASE_URL}}
   MYSQL_URL=${{MySQL.MYSQL_URL}}
   ```

### 4.2 Method 2: Service References (Recommended)

**Using Service References:**
1. In your **App Service** → **Variables** tab
2. Add variables with service references:

```
APP_KEY=base64:eZPPIOxFSMhB6HEMUlB8UVUMVv+3d91dtqAZEWP89fU=
APP_ENV=production
APP_DEBUG=false
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

**Note:** Replace `MySQL` with your actual MySQL service name from Railway dashboard.

### 4.3 Method 3: Manual Variables (Fallback)

If shared variables don't work, use manual method:
1. Get connection details from **MySQL Service** → **Connect** → **Public Network**
2. Add variables manually:

```
APP_KEY=base64:eZPPIOxFSMhB6HEMUlB8UVUMVv+3d91dtqAZEWP89fU=
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-railway-url.up.railway.app
DB_CONNECTION=mysql
DB_HOST=caboose.proxy.rlwy.net
DB_PORT=16447
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your-mysql-password
```

### 4.4 How to Add Service References:

1. **Click "New Variable"**
2. **Name**: `DB_HOST`
3. **Value**: Click **"Reference"** button
4. **Select**: Your MySQL service
5. **Choose**: `MYSQLHOST` from dropdown
6. **Result**: `${{MySQL.MYSQLHOST}}`
7. **Repeat** for all database variables

### 4.5 Verify Service Connection:

**Check Variables Tab:**
- Variables should show `${{MySQL.MYSQLHOST}}` format
- Railway automatically resolves these at runtime
- No need to copy-paste connection details manually

---

## Step 5: Deploy and Test Connection

### 5.1 Automatic Redeploy
- After adding variables, Railway will automatically redeploy
- Wait for deployment to complete (2-3 minutes)

### 5.2 Check App Status
1. Go to **"Deployments"** tab in your app service
2. Click latest deployment
3. Check **"View Logs"** for any errors
4. App should start successfully now

---

## Step 6: Run Database Migrations

### 6.1 Install Railway CLI (Local)
```bash
npm install -g @railway/cli
```

### 6.2 Connect to Railway Project
```bash
# Login to Railway
railway login

# Navigate to project folder
cd "F:\stiching project\Stitching-Erp"

# Link to Railway project
railway link
```

### 6.3 Run Migrations
```bash
# Test database connection
railway run php artisan tinker
# In tinker: DB::connection()->getPdo(); then exit

# Run migrations
railway run php artisan migrate --force

# Check migration status
railway run php artisan migrate:status
```

---

## Step 7: Import Your Database (Optional)

### 7.1 Connect to MySQL Service
```bash
# Connect to Railway MySQL
railway connect MySQL
```

### 7.2 Import Your Data
In MySQL terminal:
```sql
-- Select database
USE railway;

-- Import your SQL data (paste your SQL commands)
-- Example:
CREATE TABLE your_table (...);
INSERT INTO your_table VALUES (...);
```

---

## Step 8: Final Testing

### 8.1 Test Your Application
1. Go to your Railway app URL
2. Test all functionality
3. Check database connections
4. Verify all pages load correctly

### 8.2 Common Commands
```bash
# Clear cache
railway run php artisan cache:clear

# View logs
railway logs

# Check variables
railway variables

# Run artisan commands
railway run php artisan [command]
```

---

## 🎯 Summary of Services Created:

1. **App Service**: Your Laravel application
   - Contains your code
   - Has environment variables
   - Connects to database

2. **MySQL Service**: Your database
   - Stores your data
   - Provides connection details
   - Accessible from app service

---

## 🔧 Troubleshooting:

### App Won't Start:
- Check environment variables are set correctly
- Verify APP_KEY is set
- Check deploy logs for errors

### Database Connection Failed:
- Verify DB_HOST, DB_PORT, DB_PASSWORD are correct
- Use public network connection details
- Test connection with `railway run php artisan tinker`

### Migration Errors:
- Ensure database service is running
- Check database variables in app service
- Run `railway run php artisan config:clear`

---

## 🎉 Your Laravel App is Now Live on Railway!

**App URL**: Check your Railway dashboard for the live URL
**Database**: MySQL service connected and ready
**Migrations**: Run as needed with Railway CLI

**Next Steps**: Import your data and customize as needed!

## 🔗 Railway Service Connection (Current UI - 2024)

### Method 1: Shared Variables (Current Railway UI)

**Step-by-Step Process:**
1. **App Service** → **Variables** tab
2. Look for **"Shared Variables"** section or button
3. **Click "Shared Variables"** or **"Connect Service"**
4. **Select your MySQL service** from dropdown
5. Railway will automatically add these variables:
   ```
   MYSQLHOST=${{MySQL.MYSQLHOST}}
   MYSQLPORT=${{MySQL.MYSQLPORT}}
   MYSQLDATABASE=${{MySQL.MYSQLDATABASE}}
   MYSQLUSER=${{MySQL.MYSQLUSER}}
   MYSQLPASSWORD=${{MySQL.MYSQLPASSWORD}}
   ```

### Method 2: Manual Variable Mapping

**If Shared Variables option not visible:**
1. **App Service** → **Variables** tab
2. **"New Variable"** → Add these one by one:

```
APP_KEY=base64:eZPPIOxFSMhB6HEMUlB8UVUMVv+3d91dtqAZEWP89fU=
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

**Note:** Replace `MySQL` with your actual MySQL service name.

### Method 3: Direct Connection Details

**Fallback method:**
1. **MySQL Service** → **Connect** tab → **Public Network**
2. Copy connection details
3. **App Service** → **Variables** → Add manually:

```
DB_HOST=caboose.proxy.rlwy.net
DB_PORT=16447
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your-mysql-password
```

## 🎯 How to Use Shared Variables:

### Step 1: Enable Shared Variables
1. **App Service** → **Variables** tab
2. Look for **"Shared Variables"** or **"Connect to Service"** button
3. **Select MySQL service** from list
4. **Railway automatically creates** database variables

### Step 2: Verify Connection
After adding shared variables, you should see:
- `MYSQLHOST` variable with `${{MySQL.MYSQLHOST}}` value
- `MYSQLPORT` variable with `${{MySQL.MYSQLPORT}}` value
- And so on...

### Step 3: Map to Laravel Variables
Add these additional variables for Laravel:
```
DB_CONNECTION=mysql
DB_HOST=${{MYSQLHOST}}
DB_PORT=${{MYSQLPORT}}
DB_DATABASE=${{MYSQLDATABASE}}
DB_USERNAME=${{MYSQLUSER}}
DB_PASSWORD=${{MYSQLPASSWORD}}
```