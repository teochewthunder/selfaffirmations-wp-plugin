# Self-affirmations WordPress Plugin

This is a PHP file that needs to be saved under the directory `wp-content/plugins` with a sub-directory name of your choice.

## Oracle APEX endpoints
These are endpoints built on the Oracle APEX project for Oracle APEX [Self-affirmations Mailing List app](https://github.com/teochewthunder/oracle-apex-self-affirmations-mailing-list/tree/main).

- https://apex.oracle.com/pls/apex/teochewthunder/mailinglist/readytoreceive
    - GET
    - Collection Query
    - SELECT EMAIL, FIRST_NAME, LAST_NAME, DOB, GENDER FROM MAILING_LIST WHERE LAST_SENT < TO_DATE(CURRENT_DATE - (DAYS - 1))
- https://apex.oracle.com/pls/apex/teochewthunder/mailinglist/setreceived/:email
    - GET
    - PL/SQL
    - UPDATE MAILING_LIST SET LAST_SENT = CURRENT_DATE  WHERE EMAIL = :email
- https://apex.oracle.com/pls/apex/teochewthunder/mailinglist/terms/:email
    - GET
    - Collection Query
    - SELECT TYPE, TERM FROM MAILING_LIST_TERMS WHERE EMAIL = :email
    - 
## Plugin file
- Use `add_submenu_page` to add testing links
- `tt_get_readytoreceive()` gets a list of users who are eligible to receive email again.
- `tt_get_terms()` retrieves a JSON object for a specific user's terms, divided into Interests and Descriptions, randomly inserted from the full list.
- `tt_set_lastsent()` sets the `LAST_SENT` column for a specific user, to today's date.
- `tt_generate_email()` generates an email using ChatGPT, based on the results of `tt_get_terms()`.
- `tt_selfaffirmations()` runs `tt_get_readytoreceive()`, then runs `tt_generate_email()` on each item in the returned list. It then sends the email and runs `tt_set_lastsent()`.
  
## Emailing

## CRON job
