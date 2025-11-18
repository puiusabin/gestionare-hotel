# Sistem de Rezervari Hotel

Proiect pentru cursul de Dezvoltarea Aplicatiilor Web (PHP)

## Structura Proiect

```
hotel-reservation/
├── tema1/                   # Tema 1
│   └── index.html           # Pagina de prezentare
├── docs/                    # Documentatie
│   ├── architecture.md      # Documentatie arhitectura
│   └── diagrams/            # Diagrame UML 
│       ├── erd.puml         # Entity-Relationship Diagram
│       ├── usecase.puml     # Use Case Diagram
│       └── sequence-reservation.puml  # Sequence Diagram
├── src/                     # Cod sursa 
└── README.md
```

## Tema 1 - Descriere Aplicatie si Arhitectura

### Continut Tema 1

1. **Pagina de prezentare** (`tema1/index.html`)
   - Descriere aplicatie
   - Arhitectura sistemului
   - Roluri utilizatori
   - Entitati si relatii
   - Schema baza de date
   - Diagrame UML


3. **Diagrame UML** (`docs/diagrams/`)
   - ERD (Entity-Relationship Diagram)
   - Use Case Diagram
   - Sequence Diagram pentru procesul de rezervare

### Vizualizare Pagina Prezentare

Deschide fisierul `tema1/index.html` in browser:

## Tema 2 - Autentificare si CRUD

### Functionalitati Implementate

#### Sistem de Autentificare
- **Inregistrare utilizatori** (`/register`)
  - Validare email, parola (min 6 caractere)
  - Hashing parole cu `password_hash()`
  - Campuri obligatorii: email, parola, prenume, nume
- **Login** (`/login`)
  - Autentificare cu email si parola
  - Sesiuni PHP
  - Verificare parola cu `password_verify()`
- **Logout** (`/logout`)
  - Distrugere sesiune si redirectionare

#### Operatiuni CRUD pentru Camere

##### CREATE - Adaugare Camera (`/rooms/create`)
- Formular pentru camere noi (numar, tip, capacitate, pret, descriere)
- Validare: numar camera, tip (single/double/suite), capacitate, pret
- Acces: doar administratori
- Controller: `RoomController::store()`

##### READ - Listare Camere (`/rooms`)
- Afisare toate camerele in grid responsive
- Filtrare dupa tip: All, Single, Double, Suite
- Informatii afisate: numar camera, tip, capacitate, pret, status disponibilitate
- Controller: `RoomController::index()`

##### UPDATE - Editare Camera (`/rooms/edit?id=X`)
- Formular pre-completat cu datele camerei
- Validare identica cu CREATE
- Acces: doar administratori
- Controller: `RoomController::edit()`, `RoomController::update()`

##### DELETE - Stergere Camera (`/rooms/delete?id=X`)
- Confirmare stergere prin JavaScript
- Verificare constrangeri: camera nu poate fi stearsa daca are rezervari
- Acces: doar administratori
- Controller: `RoomController::delete()`


### Tehnologii Utilizate

- **Backend**: PHP 7.4+
- **Baza de date**: MySQL
- **Frontend**: HTML5, CSS3 
- **Securitate**:
  - `password_hash()` / `password_verify()` pentru parole
  - PDO prepared statements contra SQL injection
  - `htmlspecialchars()` contra XSS
  - Sesiuni PHP pentru autentificare

## Arhitectura Aplicatie

### Entitati Principale

1. **Users** - Utilizatori (clienti si administratori)
2. **Rooms** - Camere disponibile pentru rezervare
3. **Reservations** - Rezervari efectuate

### Roluri

- **Guest** - Utilizator inregistrat, poate face rezervari 
- **Admin** - Administrator, gestioneaza camerele 

## Autor

Proiect realizat pentru cursul de Dezvoltarea Aplicatiilor Web
