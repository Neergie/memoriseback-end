<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516202137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE author_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE book_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE book_order_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE editor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "order_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE author (id INT NOT NULL, birthday DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE book (id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, isbn VARCHAR(20) NOT NULL, price DOUBLE PRECISION NOT NULL, publish_date DATE NOT NULL, stock INT NOT NULL, ebook BOOLEAN NOT NULL, cover_image BYTEA DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE book_genre (book_id INT NOT NULL, genre_id INT NOT NULL, PRIMARY KEY(book_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_8D92268116A2B381 ON book_genre (book_id)');
        $this->addSql('CREATE INDEX IDX_8D9226814296D31F ON book_genre (genre_id)');
        $this->addSql('CREATE TABLE book_author (book_id INT NOT NULL, author_id INT NOT NULL, PRIMARY KEY(book_id, author_id))');
        $this->addSql('CREATE INDEX IDX_9478D34516A2B381 ON book_author (book_id)');
        $this->addSql('CREATE INDEX IDX_9478D345F675F31B ON book_author (author_id)');
        $this->addSql('CREATE TABLE book_order (id INT NOT NULL, book_id INT DEFAULT NULL, order_entity_id INT NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FBEB86E116A2B381 ON book_order (book_id)');
        $this->addSql('CREATE INDEX IDX_FBEB86E13DA206A5 ON book_order (order_entity_id)');
        $this->addSql('CREATE TABLE editor (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE editor_book (editor_id INT NOT NULL, book_id INT NOT NULL, PRIMARY KEY(editor_id, book_id))');
        $this->addSql('CREATE INDEX IDX_212433F86995AC4C ON editor_book (editor_id)');
        $this->addSql('CREATE INDEX IDX_212433F816A2B381 ON editor_book (book_id)');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, transaction_id INT NOT NULL, order_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivery_stats VARCHAR(255) NOT NULL, total_price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F52993982FC0CB0F ON "order" (transaction_id)');
        $this->addSql('CREATE TABLE transaction (id INT NOT NULL, payment_status VARCHAR(255) NOT NULL, payment_method VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE book_genre ADD CONSTRAINT FK_8D92268116A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_genre ADD CONSTRAINT FK_8D9226814296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D34516A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D345F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_order ADD CONSTRAINT FK_FBEB86E116A2B381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_order ADD CONSTRAINT FK_FBEB86E13DA206A5 FOREIGN KEY (order_entity_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE editor_book ADD CONSTRAINT FK_212433F86995AC4C FOREIGN KEY (editor_id) REFERENCES editor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE editor_book ADD CONSTRAINT FK_212433F816A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993982FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE author_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE book_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE book_order_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE editor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "order_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE transaction_id_seq CASCADE');
        $this->addSql('ALTER TABLE book_genre DROP CONSTRAINT FK_8D92268116A2B381');
        $this->addSql('ALTER TABLE book_genre DROP CONSTRAINT FK_8D9226814296D31F');
        $this->addSql('ALTER TABLE book_author DROP CONSTRAINT FK_9478D34516A2B381');
        $this->addSql('ALTER TABLE book_author DROP CONSTRAINT FK_9478D345F675F31B');
        $this->addSql('ALTER TABLE book_order DROP CONSTRAINT FK_FBEB86E116A2B381');
        $this->addSql('ALTER TABLE book_order DROP CONSTRAINT FK_FBEB86E13DA206A5');
        $this->addSql('ALTER TABLE editor_book DROP CONSTRAINT FK_212433F86995AC4C');
        $this->addSql('ALTER TABLE editor_book DROP CONSTRAINT FK_212433F816A2B381');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993982FC0CB0F');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE book_genre');
        $this->addSql('DROP TABLE book_author');
        $this->addSql('DROP TABLE book_order');
        $this->addSql('DROP TABLE editor');
        $this->addSql('DROP TABLE editor_book');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE transaction');
    }
}
