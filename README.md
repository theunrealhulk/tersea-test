# Tersea test setup
Assuming you already on windows and already have a stack solution <br>that include (php,nodejs,apache,mysql) like `xampp` or `wamp` or `laragon` please follow the steps below:<br><br>
after cloning the repo cd into `tersea-test` and run 
```bash
#to install the root dependencies
npm i 
#to install backend/frontend dependencies
npm run setup
#to run the app in dev mode
npm run dev
```
the app should be on http://localhost:5173
## Email testing
open `MailHog_windows_amd64.exe` to start the local email server<br>
the emails in the app in configured locally to be sent through `MailHog` at<br> http://localhost:8025