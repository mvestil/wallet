# wallet

Demo wallet app using Laravel 5.5, Vue and MySQL with basic functionalities such as creating/deleting/searching wallet for Admin, credit/debit for User

# Exceptions / Error Messages

- __ValidationException__ - Generic exception for validation errors
  - Error Messages
    - Email is required
    - Email must be a valid email address format
    - Email is already taken
    - Amount is required
    - Amount must be numeric
    - Amount must be at least 1
- InsufficientFundsException
    - Error Messages
       - Insufficient funds
- WalletNotFoundException
    - Error Messages
      - Wallet not found
