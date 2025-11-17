USE Malak_Bookstore;

INSERT INTO users (nom, email, mot_de_passe, role) VALUES
('User1 Name1', 'user1@gmail.com', 'user1@', 'user'),
('Admin1 Admin', 'admin1@gmail.com', 'admin1@', 'admin');

INSERT INTO books (titre, auteur, genre, prix, image, pdf_url, stock, description) VALUES
(
  'L\'Étranger', 'Albert Camus', 'Roman', 950.00,
  'img/books/etranger.jpeg', 'pdf/books/etranger.pdf', 20,
  'Meursault, un homme apparemment indifférent au monde qui l\'entoure, est confronté à l\'absurdité de la vie après avoir commis un meurtre. Une réflexion profonde sur l\'existence et le sens de la vie.'
),
(
  '1984', 'George Orwell', 'Science-Fiction', 1200.00,
  'img/books/1984.jpeg', 'pdf/books/1984.pdf', 15,
  'Dans un monde dystopique où règne la surveillance totale, Winston Smith tente de résister au pouvoir du Parti et de préserver sa liberté intérieure. Un classique incontournable de la littérature engagée.'
),
(
  'Le Petit Prince', 'Antoine de Saint-Exupéry', 'Conte philosophique', 800.00,
  'img/books/petit_prince.jpeg', 'pdf/books/petit_prince.pdf', 25,
  'À travers les yeux d\'un petit garçon venu d\'une autre planète, ce conte poétique aborde des thèmes universels comme l\'amitié, l\'amour et le sens de la vie, avec une simplicité touchante.'
),
(
  'Harry Potter à l\'École des Sorciers', 'J.K. Rowling', 'Fantasy', 1500.00,
  'img/books/harry_potter1.jpeg', 'pdf/books/harry_potter1.pdf', 30,
  'Harry découvre à 11 ans qu\'il est un sorcier et rejoint Poudlard, une école de magie où il vivra des aventures extraordinaires et découvrira son destin exceptionnel.'
),
(
  'Les Misérables', 'Victor Hugo', 'Classique', 1100.00,
  'img/books/les_miserables.jpeg', 'pdf/books/les_miserables.pdf', 12,
  'À travers les destins croisés de Jean Valjean, Cosette et Javert, Victor Hugo dépeint une fresque magistrale de la misère sociale et de la rédemption dans la France du XIXᵉ siècle.'
),
(
  'Le Seigneur des Anneaux : La Communauté de l\'Anneau', 'J.R.R. Tolkien', 'Fantasy', 1700.00,
  'img/books/seigneur_anneaux1.jpg', 'pdf/books/seigneur_anneaux1.pdf', 18,
  'Frodon Sacquet hérite d\'un anneau aux pouvoirs immenses et maléfiques. Avec ses compagnons, il entreprend un périlleux voyage pour détruire l\'Anneau unique avant qu\'il ne tombe entre de mauvaises mains.'
),
(
  'Le Comte de Monte-Cristo', 'Alexandre Dumas', 'Aventure', 1300.00,
  'img/books/monte_cristo.jpeg', 'pdf/books/monte_cristo.pdf', 10,
  'Victime d\'un complot, Edmond Dantès est emprisonné à tort. Après son évasion spectaculaire, il découvre un fabuleux trésor et entreprend de se venger de ceux qui l\'ont trahi.'
),
(
  'Orgueil et Préjugés', 'Jane Austen', 'Roman', 1000.00,
  'img/books/orgueil_prejuges.jpeg', 'pdf/books/orgueil_prejuges.pdf', 22,
  'Elizabeth Bennet et Mr. Darcy doivent surmonter leur orgueil et leurs préjugés pour reconnaître l\'amour véritable qui les unit. Un chef-d\'œuvre du roman anglais.'
),
(
  'La Peste', 'Albert Camus', 'Philosophie', 950.00,
  'img/books/la_peste.jpeg', 'pdf/books/la_peste.pdf', 14,
  'Dans la ville d\'Oran frappée par une épidémie de peste, les habitants doivent lutter contre la mort, mais aussi contre leur propre peur, égoïsme et solitude. Un récit métaphorique et saisissant.'
),
(
  'Le Meilleur des Mondes', 'Aldous Huxley', 'Science-Fiction', 1150.00,
  'img/books/meilleur_mondes.jpg', 'pdf/books/meilleur_mondes.pdf', 16,
  'Dans un futur où les humains sont conditionnés dès la naissance pour maintenir une société parfaite, Bernard Marx remet en cause ce bonheur artificiel et cherche un sens plus authentique à l\'existence.'
),
(
  'Notre-Dame de Paris', 'Victor Hugo', 'Classique', 1250.00,
  'img/books/notre_dame.jpg', 'pdf/books/notre_dame.pdf', 9,
  'Dans le Paris du Moyen Âge, le bossu Quasimodo lutte pour protéger la belle Esmeralda. Une fresque historique puissante et poétique qui rend hommage à la cathédrale de Notre-Dame.'
),
(
  'Da Vinci Code', 'Dan Brown', 'Thriller', 1400.00,
  'img/books/da_vinci_code.jpeg', 'pdf/books/da_vinci_code.pdf', 20,
  'Lorsque le conservateur du Louvre est assassiné, Robert Langdon est entraîné dans une course contre la montre pour découvrir un secret ancestral caché par une mystérieuse confrérie.'
),
(
  'Sapiens : Une brève histoire de l\'humanité', 'Yuval Noah Harari', 'Essai', 1600.00,
  'img/books/sapiens.jpeg', 'pdf/books/sapiens.pdf', 19,
  'Harari retrace l\'épopée de l\'humanité, de l\'émergence de l\'Homo sapiens à l\'époque moderne, en explorant les grandes révolutions cognitives, agricoles et scientifiques qui ont façonné notre monde.'
),
(
  'Mémoires d\'Hadrien', 'Marguerite Yourcenar', 'Historique', 1300.00,
  'img/books/memoires_hadrien.jpg', 'pdf/books/memoires_hadrien.pdf', 13,
  'À travers une lettre adressée à son successeur, l\'empereur Hadrien médite sur sa vie, son pouvoir, ses amours et la fragilité de l\'Empire romain. Un roman d\'une grande richesse historique et humaine.'
);


DELETE FROM users WHERE email = 'admin1@gmail.com';

