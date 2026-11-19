# Cloudflare WAF Rules for Sewa Laptop
# Configure in Cloudflare Dashboard -> Security -> WAF -> Custom Rules

## 1. Rate Limiting: Login (Anti-Brute Force)
- Rule name: "Login Rate Limit"
- Field: URI Path
- Operator: contains
- Value: /login
- Action: Block
- Rate: 10 requests per 1 minute per IP
- Duration: 1 hour

## 2. Rate Limiting: Password Reset
- Rule name: "Password Reset Rate Limit"
- Field: URI Path
- Operator: contains
- Value: /forgot-password
- Action: Block
- Rate: 5 requests per 5 minutes per IP
- Duration: 1 hour

## 3. Rate Limiting: Registration
- Rule name: "Registration Rate Limit"
- Field: URI Path
- Operator: contains
- Value: /register
- Action: Block
- Rate: 5 requests per 5 minutes per IP
- Duration: 1 hour

## 4. Bot Fight Mode
- Enable: Security -> Bots -> Bot Fight Mode (ON)
- This blocks known bad bots automatically

## 5. Cache Rules
- Rule: "Cache Static Assets"
  - If: URI Path matches *.jpg, *.png, *.css, *.js, *.woff2
  - Then: Cache Everything, TTL 1 year
- Rule: "Bypass Cache for Dynamic"
  - If: URI Path contains /login, /register, /admin, /profile, /riwayat-sewa
  - Then: Bypass Cache

## 6. Security Settings
- Security Level: High
- Browser Integrity Check: ON
- Hotlink Protection: ON (for images)
- Email Obfuscation: ON

## 7. SSL/TLS
- SSL Mode: Full (Strict)
- Always Use HTTPS: ON
- Automatic HTTPS Rewrites: ON
- Minimum TLS Version: 1.2
