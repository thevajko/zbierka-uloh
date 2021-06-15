<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/contact-form), [Riešenie](/../../tree/solution/php/contact-form).
> - [Zobraziť zadanie](zadanie.md)

# Kontaktný formulár (PHP)
</div>

## Riešenie

<div class="hidden">

> Toto riešenie obsahuje všetky potrebné služby v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktorý do __document root__ namapuje adresár tejto úlohy a nastaví php mail funkciu tak, aby odosielala maily do pripraveného MailHog servera. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.
> - MailHog server, ktorý sa automaticky prepojí s PHP na porte __8025__ a bude dostupný na adrese [http://localhost:8025/](http://localhost:8025/)
> - V prípade potreby priamého prístupu na SMTP server je tento dostupný na adrese __mailhog:1025__

</div>

Samotné riešenie je rozdelené do niekoľkých častí.

### Vytvorenie kontaktného formulára