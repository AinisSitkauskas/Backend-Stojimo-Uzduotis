# NFQ Akademija Backend Stojimo užduotis

Aliko: Ainis Šitkauskas

 Nuorodos į puslapius: 

- [ ] http://www.minidienynas.vhost.lt/client.php  - Klientų registravimo puslapis (Level 1)
- [ ] http://www.minidienynas.vhost.lt/specialist.php  - Specialisto puslapis (Level 1)
- [ ] http://www.minidienynas.vhost.lt/timetable.php  - Švieslentė (Level 1)
- [ ] http://www.minidienynas.vhost.lt/client.php?clientCode=[unikali nuoroda]  - Kliento unikalus registracijos puslapis (veikia tik turint aktyvią registraciją) Level 2
- [ ] http://www.minidienynas.vhost.lt/client.php?action=statistic  - Specialistų statistikos puslapis (Level 3)

**Trumpai apie užduotį:**

Užduotis atlikta pagal visų 3 lygių (Level 1, 2, 3) reikalavimus bei techninius kriterijus. Pridedamas duomenų bazės struktūros failas ir pavyzdynių duomenų kopija.
Toliau pateikiami trumpi komentarai apie kiekvieną puslapį:

**Svarbu** 

Šiuo metu svetainėje "dirba" 3 specialistai (Jonas Jonaitis, Petras Petraitis, Juozas Juozaitis) , o jų prisijungimo vardai yra jų vardai, o slaptažodžiai yra jų pavardės.

**Klientų registravimo puslapis**

Tai puslapis kuriame klientas įvedamas į eilę, klientas užsiregistruoja įvesdamas savo vardą ir pavardę, bei pasirinkdamas specialistą.
Sistema užregistruoja vartotją, suteikia jam unikalią nuorodą, kurios pagalba gali stebėti registracijos statusą.
Vienas klientas vienu metu gali turėti tik 1 aktyvią registraciją, taip iš šios svetainės galima patekti į statistikos puslapį su vidutiniais laukimo laikais.

**Specialisto puslapis**

Šiame puslapyje, prisijungęs specialistas gali matyti savo klientų sąrašą. Specialistas gali pasiimti laukiantį klientą, tuomet klientas gaus pranešimą jog specialistas jį priima.
Pasiėmus klientą galima pažymėti jog jis aptarnautas arba klientas neatvyko. Specialistas vienu metu gali turėti tik vieną pasiimtą klietą, o klientų eilę nustato pati sistema, jog klientai būtų imami "iš eilės".

**Švieslentė** 

Švieslentėje rodomi tik klientai, kuriuos šiuo metu aptarnauja specialistai, bei klientai kurie greičiausiai sulauksiantys savo eilės pas specialistą. 
Atvaizduojami vardai, pavardės, specialistai, bei numanomi laukimo laikai (pagal laukimo vidurkį pas specialistą). Informacija automatiškai atnaujinama kas 5 sekundės.

**Kliento puslapis** 

Pagal unikalią nuorodą, klientas gali patekti į savo registacijos puslapį, jame mato savo registacijos informaciją, bei numanomą laukimo laiką.
Informacija atnaujinama kas 5 sekundes, jei klientą pašaukia specialistas, jis tai mato savo ekrane, taip pat yra galimybė pavėlinti (sukeičia su už jo esančiu asmeniu vietomis, taip pat sukeičiama numanomus laukimo laukus tikslumui),
taip pat yra galimybė atšaukti savo registraciją bei peržvelgti specialistų aptarnavimo statistiką. 
Kliento unikalus puslapis veikia tik klientui laukiant savo eilės arba kai jį klientas priima, aptarnauto ir atšaukusio kliento informacija neberodoma (nukreipiama į registacijos puslapį)

**Statistikos puslapis**

Rodoma specialistų aptarnavimo statistika, kiek klientai laukia, kol specialistas juos priima. Galima rinktis pagal specialistą ir laikai rodomi kiekvienos savaitės dienos atskirai.
Laikai įtraukti tik klientų, kurie buvo aptarnauti - laukiančių, paimtų ar atšaukusių klientų statistikoje nerodoma.

