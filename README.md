# Haus Mieten Finder

## WTF?

Haus Mieten Finder helps you find accomodations (houses only for now) next to a specified address sorting all results by the time it takes to get there with public transport.

Sample search result: http://bit.ly/1woWf3O

## How?

The UI uses Google Places autocomplete API to get LAT/LON, that are then fed to ImmobiliarenScout24 to get a list of all possible houses in the range of 75km. Each house location then is fed to Google Directions API (limit of 2k a day :( ) to get the fastest possible route by public transit on the next Tuesday morning at 7 AM. Why Tuesday morning at 7 AM? 

Just guessing it would be a time where you could get a good estimate of commuting times...

## Search lifecycle

1. Session opens and connects to NodeJS - receiving information regarding the server specific RabbitMQ queue and the client socket id
2. User types the address and triggers a new search
3. Search data is saved straight into MongoDB and a new task is queued up in RabbitMQ for a new search
4. PHP workers pick up the task, work on it (5-10 mins) saving results on MongoDB and then push a signal to the Node server specific RabbitMQ queue (using the stanard acknowledgement pattern)
5. Node picks up the message from the queue, sends a ping through websockets to the client that now can redirect the user to the search page (a simple page that shows paged results and allows the user to shortlist them)

## Really, why?

I had two main goals: experience a new stack of technologies ASAP (= a few hours work), a tiny bit of German, and find a better way to list available houses next to my place of work sorted by public transportation distance. ImmobiliarenScout24 is probably the best resource to find accomodation - but, probably because of Google Directions API limitations, cannot provide accurate 'time to destination by public transport'.

I had to find a better way to list all houses and shortlist the best one according to my criteria (kitchen available, warm rent < X,XXX and 0 or 1 months provision for agents). 

This small framework helped me deploy something quickly and save me immensely a lot of time house hunting.

## Stack
(all up to the latest version at the time of writing)

- Server - Web framework: Phalcon
- Client - UI: React (pre-compiled w/ jsx compiler), LESS
- Websockets communication: Node JS + Socket.IO
- Sessions: Redis
- DB: MongoDB
- Task scheduler for high intensity jobs: RabbitMQ
- Infrastructure: Amazon EC2 - Ubuntu HMV

## And then?

- Set up Puppet Enterprise on another Ubuntu HMV host and schedule multi-stack deployment of RabbitMQ workers, Web tiers, etc...
- Deployment of concatenated and minified assets to Amazon S3, with delivery through Cloudfront
- Automaticaly translate descriptions German > English using Google Translator API
- Allow search by apartments as well
