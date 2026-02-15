# Fix .env File Error

## Problem
```
The environment file is invalid!
Failed to parse dotenv file. Encountered unexpected whitespace at [egpy qobf enoz ylto].
```

## Solution

The `MAIL_PASSWORD` value in your `.env` file has spaces (which is normal for Google App Passwords), but it needs to be wrapped in quotes.

### ❌ WRONG (causes error):
```env
MAIL_PASSWORD=abcd efgh ijkl mnop
```

### ✅ CORRECT (use quotes):
```env
MAIL_PASSWORD="abcd efgh ijkl mnop"
```

### ✅ ALSO CORRECT (remove spaces):
```env
MAIL_PASSWORD=abcdefghijklmnop
```

## Complete .env Mail Configuration

Add or update these lines in your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME="your-email@gmail.com"
MAIL_PASSWORD="your-app-password-with-spaces"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="SGOD - Records Management System"
```

**Important Notes:**
1. Wrap values with spaces in double quotes `"`
2. You can also remove spaces from the app password (both work)
3. Make sure there are no extra spaces before or after the `=` sign
4. No trailing spaces at the end of lines

## After Fixing

1. Save the `.env` file
2. Clear config cache:
   ```bash
   php artisan config:clear
   ```
3. Restart your server if it's running

