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

## Arhitectura Aplicatie

### Entitati Principale

1. **Users** - Utilizatori (clienti si administratori)
2. **Rooms** - Camere disponibile pentru rezervare
3. **Reservations** - Rezervari efectuate

### Roluri

- **Guest** - Poate face rezervari si le poate gestiona
- **Admin** - Poate gestiona camerele

### Tehnologii Propuse

- Backend: PHP 7.4+, MySQL
- Frontend: HTML5, CSS3, JavaScript
- Securitate: password_hash, PDO prepared statements
- Integrari: PHPMailer, FPDF, API extern


## Autor

Proiect realizat pentru cursul de Dezvoltarea Aplicatiilor Web
