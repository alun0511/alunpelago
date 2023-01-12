![image](https://64.media.tumblr.com/5313f4c87542698f6e146cc1c333210a/tumblr_mlo88g8ymk1s5bh5uo1_500.gifv)

# North-east Koster

In the beautiful archipelago of Bohuslehn the picturesque island of Pelago can be found. With sprawling nature and wildlife above and below the sea it makes the perfect retreat from the bustling city-life. 

# Moster Dagnys

20 feet from the shore a small white-blue cottage can be found. Moster Dagnys has remained the same since it was built nearly a century ago, despite the modernization of surrounding buildings. Dive in to the deep blue sea or ride it on a jet ski, walk around the island on car free roads and breathe the salty breeze. 

# Instructions

If the website looks strange please try deleting cache and history or change browser. There is something wrong with the stylesheets in especially firefox. 

# Code review
Axel Enghamre

1. index.php:1-7 - alla php filer bör vara i strikt läge
2. hotelFunctions.php:6 - calendar har mer med index.php att göra så den kan inkluderas där istället.
3. calendar.php:- denna fil bör också vara i strikt läge.
4. validator.php:- denna fil gör ingeting så den kan bort.
5. booking.php:10 - homePage används aldrig så denna variabeln kan tas bort.
6. booking.php:12 - 59 - eftersom denna if kontrollerar input bör ett fel-medlande ges om den inte uppfylls.
7. booking.php:37 - insertDate bör ske efter kontroll av result från deposit.
8. script.js:13 - 32 - var bör inte användas utan antingen const eller let.
9. script.js:30 - detta behöver inte loggas för användaren.
10. hotelFunctions.php: - funktioner som inte returnerar något bör ha void som retur typ.

