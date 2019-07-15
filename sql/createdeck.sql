create table decks(
	deck_id int not null auto_increment,
    deckname varchar(100),
    description varchar(255),
    clan varchar(100),
    -- timesrated int,
    -- rating float,
    primary key (deck_id)
);
select * from decks;
select * from ownsdeck;
-- delete from decks where deck_id = 7 or deck_id = 8;
-- insert into ownsdeck (user_id, deck_id) values (4, 2);
select * from cards_all;

SELECT MAX(deck_id) FROM decks;

-- could add date created
-- add rating, comments

create table deck_ratings(
	rating_id int not null auto_increment,
    deck_id int not null,
    user_id int not null,
    rating float,
    primary key (rating_id),
    foreign key (deck_id) references decks(deck_id),
    foreign key (user_id) references users(id)
);
-- drop table deck_ratings;
-- retrieving rating and getting average
-- https://stackoverflow.com/questions/2892705/how-do-i-model-product-ratings-in-the-database
select * from deck_ratings;

SELECT AVG(dr.rating) AS rating_average -- or ROUND(AVG(pr.rating))
FROM deck_ratings dr
INNER JOIN decks d
  ON dr.deck_id = d.deck_id
  AND d.deck_id = "2";
  
SELECT AVG(dr.rating) AS rating_average -- or ROUND(AVG(pr.rating))
FROM deck_ratings dr
INNER JOIN decks d
  ON dr.deck_id = d.deck_id
INNER JOIN users u
  ON dr.user_id = u.id
  AND d.deck_id = "2";


-- where to put on cascade deletion?
-- if you delete a user, do you want to delete their decks?
-- if you deleted a deck, you definitely don't want to delete the cards

create table ownsdeck(
	ownsdeck_id int not null auto_increment,
    user_id int not null,
    deck_id int not null,
    primary key (ownsdeck_id),
    foreign key (user_id) references users(id),
    foreign key (deck_id) references decks(deck_id) -- on delete cascade
);

select * from ownsdeck;

SELECT * 
FROM decks d
INNER JOIN ownsdeck od
    on d.deck_id = od.deck_id
INNER JOIN users u
    on od.user_id = u.id
WHERE u.id=4;

create table deckcontains(
	deckcontains_id int not null auto_increment,
    deck_id int not null,
    cards_id int not null,
    primary key (deckcontains_id),
    foreign key (deck_id) references decks(deck_id), -- on delete cascade
    foreign key (cards_id) references cards_all(cards_id)
);

-- SELECT * FROM deckcontains;

-- create trigger four_card_limit_on_deckcontains 
-- before insert on deckcontains
-- FOR EACH ROW

select COUNT(*) as inserted_cards from deckcontains where deck_id=2 and cards_id=3202;

SELECT * 
FROM decks d
INNER JOIN deckcontains dc
    on d.deck_id = dc.deck_id
INNER JOIN cards_all c
    on dc.cards_id = c.cards_id
WHERE d.deck_id = 1;

select * from deckcontains;
select deckcontains_id from deckcontains where deck_id=6 and cards_id=6564 limit 1;
-- delete from deckcontains where deck_id=6 and cards_id=6564;
-- delete from deckcontains where deckcontains_id = (select deckcontains_id from deckcontains where deck_id=6 and cards_id=6564 limit 1);
select * from deckcontains dc 
inner join (select deckcontains_id from deckcontains where deck_id=6 and cards_id=6564 limit 1) dcid
on dc.deckcontains_id = dcid.deckcontains_id;
-- deleting a card from deck
-- delete dc.* from deckcontains dc where deckcontains_id in (select deckcontains_id from (select deckcontains_id from deckcontains where deck_id=6 and cards_id=4872 limit 1) x);

-- Add message column to deck-ratings
ALTER TABLE deck_ratings
ADD message VARCHAR(255);