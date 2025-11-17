CREATE DATABASE IF NOT EXISTS Malak_Bookstore;
USE Malak_Bookstore;

-- Table des utilisateurs (clients et administrateurs)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

-- Table des livres
CREATE TABLE books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255), -- Lien vers l'image de couverture
    pdf_url VARCHAR(255) -- Lien vers le fichier PDF
);

-- Table du panier
CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des articles dans le panier
CREATE TABLE cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cart_id INT NOT NULL,
    book_id INT NOT NULL,
    quantité INT NOT NULL DEFAULT 1,
    FOREIGN KEY (cart_id) REFERENCES cart(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- Table des commandes
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    statut ENUM('en attente', 'expédiée', 'livrée', 'annulée') DEFAULT 'en attente',
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des articles dans une commande
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    book_id INT NOT NULL,
    quantité INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- Table des messages de contact
CREATE TABLE messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



USE Malak_Bookstore;

-- Ajout du champ stock dans la table books
ALTER TABLE books ADD stock INT NOT NULL DEFAULT 10;
ALTER TABLE books ADD description TEXT;

-- Création d'une table pour l'historique des commandes annulées
CREATE TABLE canceled_orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    date_commande TIMESTAMP,
    date_annulation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    raison VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Création d'une table pour l'historique des articles des commandes annulées
CREATE TABLE canceled_order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    canceled_order_id INT NOT NULL,
    book_id INT NOT NULL,
    quantité INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (canceled_order_id) REFERENCES canceled_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);                                                                                                                                                                                  DELIMITER //

-- Procédure stockée pour afficher les détails d'une commande pour un client
CREATE PROCEDURE GetOrderDetails(IN p_order_id INT, IN p_user_id INT)
BEGIN
    -- Vérifier que la commande appartient bien à l'utilisateur
    DECLARE user_valid INT;
    
    SELECT COUNT(*) INTO user_valid 
    FROM orders 
    WHERE id = p_order_id AND user_id = p_user_id;
    
    IF user_valid > 0 THEN
        -- Informations de la commande
        SELECT o.id, o.date_commande, o.statut, o.total
        FROM orders o
        WHERE o.id = p_order_id;
        
        -- Détails des articles de la commande
        SELECT oi.book_id, b.titre, b.auteur, oi.quantité, oi.prix_unitaire, 
               (oi.quantité * oi.prix_unitaire) AS sous_total
        FROM order_items oi
        JOIN books b ON oi.book_id = b.id
        WHERE oi.order_id = p_order_id;
    ELSE
        SELECT 'Commande non trouvée ou non autorisée' AS message;
    END IF;
END //

-- Procédure stockée pour finaliser une commande et vider le panier
CREATE PROCEDURE FinalizeOrder(IN p_user_id INT, OUT p_order_id INT)
BEGIN
    DECLARE cart_exists INT;
    DECLARE cart_id_val INT;
    DECLARE cart_total DECIMAL(10,2);
    
    -- Vérifier si l'utilisateur a un panier
    SELECT COUNT(*), id, total INTO cart_exists, cart_id_val, cart_total
    FROM cart 
    WHERE user_id = p_user_id;
    
    IF cart_exists > 0 THEN
        -- Créer la commande
        INSERT INTO orders (user_id, total, statut)
        VALUES (p_user_id, cart_total, 'en attente');
        
        SET p_order_id = LAST_INSERT_ID();
        
        -- Transférer les articles du panier vers la commande
        INSERT INTO order_items (order_id, book_id, quantité, prix_unitaire)
        SELECT p_order_id, ci.book_id, ci.quantité, b.prix
        FROM cart_items ci
        JOIN books b ON ci.book_id = b.id
        WHERE ci.cart_id = cart_id_val;
        
        -- Vider le panier
        DELETE FROM cart_items WHERE cart_id = cart_id_val;
        UPDATE cart SET total = 0 WHERE id = cart_id_val;
        
        SELECT 'Commande créée avec succès' AS message;
    ELSE
        SET p_order_id = 0;
        SELECT 'Panier vide ou inexistant' AS message;
    END IF;
END //

-- Procédure pour afficher l'historique des commandes d'un client
CREATE PROCEDURE GetOrderHistory(IN p_user_id INT)
BEGIN
    -- Commandes actives
    SELECT o.id, o.date_commande, o.statut, o.total,
           COUNT(oi.id) AS nombre_articles
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = p_user_id
    GROUP BY o.id
    ORDER BY o.date_commande DESC;
    
    -- Commandes annulées
    SELECT co.id, co.date_commande, 'annulée' AS statut, co.total,
           COUNT(coi.id) AS nombre_articles, co.raison
    FROM canceled_orders co
    LEFT JOIN canceled_order_items coi ON co.id = coi.canceled_order_id
    WHERE co.user_id = p_user_id
    GROUP BY co.id
    ORDER BY co.date_commande DESC;
END //

-- Trigger pour mettre à jour le stock après validation d'une commande
CREATE TRIGGER after_order_items_insert
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    -- Mettre à jour le stock
    UPDATE books
    SET stock = stock - NEW.quantité
    WHERE id = NEW.book_id;
END //

-- Trigger qui empêche l'insertion d'une commande si quantité > stock
CREATE TRIGGER before_order_items_insert
BEFORE INSERT ON order_items
FOR EACH ROW
BEGIN
    DECLARE available_stock INT;
    
    -- Récupérer le stock disponible
    SELECT stock INTO available_stock
    FROM books
    WHERE id = NEW.book_id;
    
    -- Vérifier si assez de stock
    IF NEW.quantité > available_stock THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Stock insuffisant';
    END IF;
END //

-- Trigger pour restaurer le stock après annulation d'une commande
CREATE PROCEDURE CancelOrder(IN p_order_id INT, IN p_user_id INT, IN p_raison VARCHAR(255))
BEGIN
    DECLARE user_valid INT;
    
    -- Vérifier que la commande appartient à l'utilisateur
    SELECT COUNT(*) INTO user_valid 
    FROM orders 
    WHERE id = p_order_id AND user_id = p_user_id;
    
    IF user_valid > 0 THEN
        -- Insérer dans la table des commandes annulées
        INSERT INTO canceled_orders (order_id, user_id, total, date_commande, raison)
        SELECT id, user_id, total, date_commande, p_raison
        FROM orders
        WHERE id = p_order_id;
        
        SET @canceled_id = LAST_INSERT_ID();
        
        -- Insérer les articles dans la table des articles annulés
        INSERT INTO canceled_order_items (canceled_order_id, book_id, quantité, prix_unitaire)
        SELECT @canceled_id, book_id, quantité, prix_unitaire
        FROM order_items
        WHERE order_id = p_order_id;
        
        -- Restaurer le stock
        UPDATE books b
        JOIN order_items oi ON b.id = oi.book_id
        SET b.stock = b.stock + oi.quantité
        WHERE oi.order_id = p_order_id;
        
        -- Supprimer la commande
        DELETE FROM order_items WHERE order_id = p_order_id;
        DELETE FROM orders WHERE id = p_order_id;
        
        SELECT 'Commande annulée avec succès' AS message;
    ELSE
        SELECT 'Commande non trouvée ou non autorisée' AS message;
    END IF;
END //

DELIMITER ; 

DROP PROCEDURE IF EXISTS FinalizeOrder;

DELIMITER //

CREATE PROCEDURE FinalizeOrder(IN p_user_id INT, OUT p_order_id INT)
BEGIN
    DECLARE cart_exists INT DEFAULT 0;
    DECLARE cart_id_val INT;
    DECLARE cart_total DECIMAL(10,2);

    -- Vérifier s'il existe un panier
    SELECT COUNT(*) INTO cart_exists FROM cart WHERE user_id = p_user_id;

    IF cart_exists > 0 THEN
        SELECT id, total INTO cart_id_val, cart_total
        FROM cart 
        WHERE user_id = p_user_id
        LIMIT 1;

        -- Créer la commande
        INSERT INTO orders (user_id, total, statut)
        VALUES (p_user_id, cart_total, 'en attente');

        SET p_order_id = LAST_INSERT_ID();

        -- Transférer les articles du panier vers la commande
        INSERT INTO order_items (order_id, book_id, quantité, prix_unitaire)
        SELECT p_order_id, ci.book_id, ci.quantité, b.prix
        FROM cart_items ci
        JOIN books b ON ci.book_id = b.id
        WHERE ci.cart_id = cart_id_val;

        -- Vider le panier
        DELETE FROM cart_items WHERE cart_id = cart_id_val;
        UPDATE cart SET total = 0 WHERE id = cart_id_val;

        SELECT 'Commande créée avec succès' AS message;
    ELSE
        SET p_order_id = 0;
        SELECT 'Panier vide ou inexistant' AS message;
    END IF;
END //

DELIMITER ;

DROP TRIGGER IF EXISTS after_order_items_insert;

DELIMITER //

CREATE PROCEDURE FinalizeOrder(IN p_user_id INT, OUT p_order_id INT)
BEGIN
    DECLARE cart_exists INT DEFAULT 0;
    DECLARE cart_id_val INT;
    DECLARE cart_total DECIMAL(10,2);

    -- Vérifier s'il existe un panier
    SELECT COUNT(*) INTO cart_exists FROM cart WHERE user_id = p_user_id;

    IF cart_exists > 0 THEN
        SELECT id, total INTO cart_id_val, cart_total
        FROM cart 
        WHERE user_id = p_user_id
        LIMIT 1;

        -- Créer la commande
        INSERT INTO orders (user_id, total, statut)
        VALUES (p_user_id, cart_total, 'en attente');

        SET p_order_id = LAST_INSERT_ID();

        -- Transférer les articles du panier vers la commande
        INSERT INTO order_items (order_id, book_id, quantité, prix_unitaire)
        SELECT p_order_id, ci.book_id, ci.quantité, b.prix
        FROM cart_items ci
        JOIN books b ON ci.book_id = b.id
        WHERE ci.cart_id = cart_id_val;

        -- Mise à jour du stock directement ici
        UPDATE books
        SET stock = stock - (
            SELECT ci.quantité
            FROM cart_items ci
            WHERE ci.book_id = books.id AND ci.cart_id = cart_id_val
        )
        WHERE books.id IN (
            SELECT book_id FROM cart_items WHERE cart_id = cart_id_val
        );

        -- Vider le panier
        DELETE FROM cart_items WHERE cart_id = cart_id_val;
        UPDATE cart SET total = 0 WHERE id = cart_id_val;

        SELECT 'Commande créée avec succès' AS message;
    ELSE
        SET p_order_id = 0;
        SELECT 'Panier vide ou inexistant' AS message;
    END IF;
END //

DELIMITER ;
