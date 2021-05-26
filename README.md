# order-form

Goal:
Make a form with validation for a webshop (sandwich shop). Customer can order various sandwiches/drinks.
You will add a counter at the bottom of the page that shows the total amount of money that has been spent on this page for this user. This counter should keep going up even when the user closes his browser.

Learning objectives
- Be able to tell the difference between the superglobals $_GET, $_POST, $_COOKIE and $_SESSION variable.
- Be able to write basic validation for PHP.


Required features:
- validation:
    - no empty fields - used Bootstrap alerts for this
    - valid email  
    - street/city = no numbers
    - zipdcode/streetnumber = no text
- save user address, we will use a SESSION variable for this 
- switching between drinks/food (the name of the array is the same)
- calculate delivery time
- total revenue counter that shows the total amount of money that has been spent on this page for this user