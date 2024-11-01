=== tpay Payment Gateway for WP e-Commerce ===
Contributors: tpay.com
Tags: tpay, merchant, payment gateway, wpec, wp e-commerce, e-commerce, polish gateway, polska brama płatności
Requires at least: 3.0.1
Tested up to: 5.7
Stable tag: 2.0.1
License: GPLv2

Accept payments from all major polish banks directly on your WP - ecommerce site via tpay.com polish payment gateway system.

== Description ==

tpay.com to system szybkich płatności online należący do spółki Krajowy Integrator Płatności SA. Misją przedsiębiorstwa jest wprowadzanie oraz propagowanie nowatorskich metod płatności i rozwiązań płatniczych zapewniających maksymalną szybkość i bezpieczeństwo dokonywanych transakcji.

Jako lider technologiczny, tpay.com oferują największą liczbę metod płatności na rynku. W ofercie ponad 50 sposobów zapłaty znajdą Państwo m.in. największy wybór e-transferów, Zintegrowaną Bramkę Płatności Kartami, mobilną galerię handlową RockPay oraz narzędzie do zbiórek pieniężnych w sieci – serwis eHat.me. Dodatkowe funkcjonalności systemu obejmują pełen design w RWD, przelewy masowe oraz udostępnione biblioteki mobilne i dodatki do przeglądarek automatyzujące przelewy. tpay.com oferuje również płatności odroczone, raty online Premium SMS oraz płatność za pomocą kodu QR.

tpay.com zapewnia najwyższy poziom bezpieczeństwa potwierdzony certyfikatem PCI DSS Level 1. System gwarantuje wygodę oraz możliwość natychmiastowej realizacji zamówienia. Oferta handlowa tpay.com jest dokładnie dopasowana do Twoich potrzeb.

tpay.com Online Payment System belongs to Krajowy Integrator Płatności Inc. The company’s mission is to introduce and promote innovative payment methods and solutions ensuring maximum speed and safety of online transactions.

As technological leader, tpay.com offers the largest number of payment methods on market. Among over 50 ways of finalizing transactions you will find the widest choice of direct online payments, Integrated Card Payment Gate, mobile shopping center – RockPay and group payments tool – eHat.me. Additional features include: RWD design, mass pay-outs, mobile libraries and payment automation application. You can also pay using postponed payment, online installments, Premium SMS and QR code payment.

The highest level of security of payments processed by tpay.com is verified by PCI DSS Level 1 certificate. System guarantees convenience and instant order execution. Our business offer is flexible and prepared according to your needs.



== Installation ==

= WYMAGANIA =

Aby korzystać z płatności tpay.com w platformie WP eCommerce  niezbędne jest:
a)	Posiadanie konta w systemie tpay.com
b)	Aktywna wtyczka WP eCommerce  dla Wordpressa.
c)	Wersja serwera PHP minimum 5.6



= INSTALACJA MODUŁU =
-  Instalacja automatyczna 
a)	Przejdź do „Wtyczki” następnie „Dodaj nową” i w miejscu „Szukaj wtyczek”  wyszukaj „tpay WP eCommerce”
b)	W „Wynikach wyszukiwania” pojawi się moduł płatności tpay, który należy zainstalować. 


-  Instalacja ręczna 
a)	Rozpakuj zawartość archiwum na dysk. Po rozpakowaniu powinien powstać folder „ transferuj-payment-gateway-for-wp-e-commerce”.
b)	Wyślij cały folder  do katalogu wp-content/plugins znajdującego się w Twojej instalacji Wordpress.

1.	Przejdź do panelu administracyjnego i otwórz zakładkę „Wtyczki”. Kliknij „Włącz” przy pozycji „tpay Payment Gateway for WP e-Commerce ”.
2.	Przejdź do „Ustawienia” i wybierz „Sklep” .
po czym z listy dostępnych opcji należy wybrać zakładkę „Płatności”. 

Teraz należy włączyć moduł tpay zaznaczając dostępne  przy nazwie pole oraz dokonać odpowiednich ustawień dla modułu płatności   wybierając opcję „Ustawienia”:
 

Poniżej znajdują się Ustawienia dla modułu tpay.com. Jeśli nie posiadasz konta w systemie tpay.com możesz je zarejestrować klikając w odnośnik „Zarejestruj konto w systemie tpay.com”.

a. Wyświetlana nazwa – należy wpisać tpay.com
b. ID Sprzedawcy – jest to ID nadane Sprzedawcy podczas rejestracji konta w systemie tpay.com, służy jako login podczas logowania do Panelu Odbiorcy Płatności w systemie tpay.com
c. Kod bezpieczeństwa – kod, który jest dostępny w Panelu Odbiorcy Płatności w systemie tpay.com w zakładce „Ustawienia” -> „Powiadomienia” sekcja „Bezpieczeństwo”. 
d. Wybór kanału płatności – opcja, która pozwala Sprzedawcy formę wyboru kanału płatności przez Klienta sklepu. Dostępne są trzy warianty wyboru kanału płatności, a zalecaną i jednocześnie metodą domyślną jest opcja „Ikony banków na stronie sklepu”. 


= Testy =

Moduł był testowany na systemie zbudowanym z wersji WP eCommerce  3.12.4 i Wordpress 4.8.2.

= KONTAKT =

W razie potrzeby odpowiedzi na pytania powstałe podczas lektury lub szczegółowe wyjaśnienie kwestii technicznych prosimy o kontakt poprzez formularz znajdujący się w Panelu Odbiorcy lub na adres e-mail: pt@tpay.com 

== Changelog ==
v2.0.1
Dodano obsługę nowego mechanizmu sum kontrolnych
v2.0
Przebudowano moduł i oparto funkcjonalność na bibliotekach PHP tpay.com
Prosimy przed istalacją upewnić się, że serwer ma wersję PHP minimum 5.6
Reorganizacja kodu źródłowego
Implementacja bibliotek tpay.com
Nowy design wtyczki
Poprawa błędów wyświetlania
Poprawa bezpieczeństwa wtyczki

== Upgrade Notice ==

= 2.0 =
2.0 Wersja 2.0 jest dużą poprawką, przed instalacją prosimy upewnić się, że Twój sklep działa na wersji PHP 5.6 lub wyższej! Po aktualizacji należy ponownie włączyć wtyczkę w menu zainstalowanych dodatków.
