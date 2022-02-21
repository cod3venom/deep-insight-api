<?php

namespace App\DataFixtures;

use App\Entity\Contact\ContactCompany;
use App\Entity\Contact\ContactProfile;
use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class SubUserFixtures extends Fixture
{
    private const emails = [
        'brad.lea@lightspeedvt.com',
        'sub.mark.zuckerberg@facebook.com',
        'donald@trump.com',
        'elon.musk@spacex.com',
        'jeff.bezos@amazon.com',
        'jeffery.kub@hotmail.com',
        'xsatterfield@hotmail.com',
        'nick81@ernser.biz',
        'xmosciski@cruickshank.com',
        'lortiz@hintz.com',
        'nreichert@roberts.org',
        'yhand@mraz.com',
        'chadd01@gmail.com',
        'schneider.pat@gmail.com',
        'swaniawski.ryan@senger.com',
        'astrid79@blanda.biz',
        'erdman.angie@gmail.com',
        'hlangworth@hotmail.com',
        'mschuppe@fritsch.org',
        'estrella.oreilly@gmail.com',
        'dawson25@yahoo.com',
        'gerry98@lesch.com',
        'freddy66@kozey.com',
        'boehm.julianne@hotmail.com',
        'sdonnelly@kihn.com',
        'sven55@abernathy.com',
        'vita.runolfsdottir@gmail.com',
        'blick.brennan@konopelski.info',
        'eugene18@balistreri.com',
        'rice.rosie@gmail.com',
        'erling64@howe.com',
        'hertha.mclaughlin@okuneva.com',
        'morissette.cory@yahoo.com',
        'ebba.ward@west.net',
        'ulises99@green.com',
        'krajcik.laverne@wisoky.com',
        'zroob@yahoo.com',
        'trantow.sterling@hotmail.com',
        'vena94@yahoo.com',
        'vsanford@hotmail.com',
        'gia95@gmail.com',
        'ebreitenberg@hotmail.com',
        'ttorp@hotmail.com',
        'loyce.cole@yahoo.com',
        'xstrosin@altenwerth.net',
        'helene.reichel@yahoo.com',
        'americo.armstrong@gmail.com',
        'adicki@hoppe.org',
        'elliott.ohara@douglas.com',
        'cleo.green@rutherford.com',
        'corine.koepp@hotmail.com',
        'jsenger@gmail.com',
        'williamson.damian@yahoo.com',
        'enrico55@gerhold.com',
        'fbeier@gmail.com',
        'hamill.haley@zboncak.biz',
        'rfarrell@ziemann.org',
        'hwalter@yahoo.com',
        'corkery.layne@hotmail.com',
        'ruecker.estella@marks.com',
        'perdman@gusikowski.com',
        'kstoltenberg@hoppe.org',
        'chermiston@gmail.com',
        'zmckenzie@greenholt.biz',
        'reinger.wilber@mraz.com',
        'marjory51@schroeder.biz',
        'julia33@lynch.info',
        'kdeckow@stark.com',
        'pink16@gmail.com',
        'marisa.labadie@nader.com',
        'lulu29@yahoo.com',
        'feeney.alejandra@yahoo.com',
        'monte51@hane.biz',
        'mcclure.evert@durgan.biz',
        'reichert.keshawn@lehner.org',
        'buckridge.adelia@yahoo.com',
        'mohr.alta@gmail.com',
        'ziemann.oswaldo@hintz.biz',
        'turcotte.hipolito@hotmail.com',
        'tamia06@gmail.com',
        'garrick52@hotmail.com',
        'yvandervort@haley.com',
        'skshlerin@gmail.com',
        'rruecker@gmail.com',
        'klocko.raphaelle@bahringer.com',
        'elouise63@little.com',
        'marquise20@graham.biz',
        'zemlak.valentina@hotmail.com',
        'vstanton@gmail.com',
        'dietrich.amalia@barton.net',
        'deontae.mosciski@yahoo.com',
        'runolfsson.megane@yahoo.com',
        'ohara.korbin@wolf.com',
        'braeden55@stracke.com',
        'qgraham@hotmail.com',
        'considine.marianna@jacobs.org',
        'elnora97@runte.com',
        'gwolff@yahoo.com',
        'zulauf.citlalli@hotmail.com',
        'abbott.dameon@heller.com',
        'beer.jerrold@renner.info',
        'monica.raynor@kohler.com',
        'ova25@hotmail.com',
        'wfadel@klein.com',
        'bstrosin@feil.info',
        'merritt.jerde@gmail.com',
        'marcelina.smith@kiehn.com',
        'orn.emely@jaskolski.com',
        'jayda.breitenberg@greenfelder.com',
        'rashawn66@metz.net',
        'winnifred.murray@wiegand.com',
        'vella.lockman@bradtke.net',
        'stroman.jacinthe@yahoo.com',
        'ford.langosh@gmail.com',
        'sauer.lavon@hayes.biz',
        'earl.stehr@yahoo.com',
        'qrippin@rohan.net',
        'morris25@gmail.com',
        'kozey.richard@huels.com',
        'kulas.jonathon@hotmail.com',
        'kuvalis.herminia@barton.com',
        'alena.ondricka@gmail.com',
        'celestino18@gislason.com',
        'zelma06@bauch.biz',
        'eldon.labadie@kuhlman.org',
        'berenice64@yahoo.com',
        'collier.lonzo@gmail.com',
        'ryan.schuppe@gmail.com',
        'oberbrunner.neil@yahoo.com',
        'hudson.lester@gmail.com',
        'ignacio14@gmail.com',
        'adolfo02@roberts.com',
        'herzog.darrion@hotmail.com',
        'martin89@gmail.com',
        'xwuckert@hotmail.com',
        'nroob@gmail.com',
        'keyshawn97@wyman.com',
        'hermina.connelly@conroy.com',
        'myra02@gmail.com',
        'elang@harris.net',
        'walter.stanton@hotmail.com',
        'darian12@gusikowski.biz',
        'murazik.davon@haley.net',
        'royal20@considine.com',
        'eichmann.michel@adams.com',
        'pearline25@mraz.net',
        'euna51@hotmail.com',
        'ymoore@beatty.com',
        'cjacobson@dubuque.biz',
        'hayley95@stark.com',
        'jacobson.donnell@roberts.com',
        'schiller.jeffry@yahoo.com',
        'elenor55@mante.com',
        'laisha.schuppe@strosin.info',
        'ward.lamont@bailey.com',
        'jaqueline.grant@hotmail.com',
        'lia36@graham.com',
        'king.giovanny@yahoo.com',
        'mustafa.schinner@gislason.net',
        'greenholt.euna@gmail.com',
        'vpadberg@hotmail.com',
        'madison54@yahoo.com',
        'dasia95@zieme.com',
        'melyssa.casper@yahoo.com',
        'britney.flatley@yahoo.com',
        'elwin.mertz@bashirian.com',
        'beatty.virginia@huels.com',
        'osinski.mason@jones.com',
        'lawson52@hotmail.com',
        'collins.hildegard@mills.com',
        'brycen.kshlerin@yahoo.com',
        'zwuckert@yahoo.com',
        'mallory23@yahoo.com',
        'ali27@yahoo.com',
        'vmclaughlin@gmail.com',
        'alden.spencer@yahoo.com',
        'sabina94@hotmail.com',
        'bjohnson@yahoo.com',
        'kris.saige@gmail.com',
        'rosina.russel@hotmail.com',
        'rjenkins@treutel.com',
        'angel28@hotmail.com',
        'nbatz@johnston.com',
        'tbecker@gmail.com',
        'goyette.flossie@hotmail.com',
        'dave.cummings@gmail.com',
        'lottie.gaylord@hotmail.com',
        'hmitchell@kuphal.biz',
        'gloria26@hoeger.org',
        'hahn.jimmie@bartell.net',
        'bergnaum.keyshawn@yahoo.com',
        'naomi39@gmail.com',
        'lynch.garfield@harber.biz',
        'jadyn.hilpert@gleason.com',
        'cprosacco@macejkovic.org',
        'ned.nienow@yahoo.com',
        'trinity15@hotmail.com',
        'noah.ohara@yahoo.com',
        'quitzon.genesis@jacobs.com',
        'ronny19@hotmail.com',
        'keegan.bauch@yahoo.com',
        'prohaska.elda@treutel.biz',
        'mills.rhiannon@kohler.com',
        'jtremblay@yahoo.com',
        'tblanda@farrell.com'
    ];

    private const names = [
        'Brad Lea',
        'Mark Zuckerberg',
        'Donald Trump',
        'Elon Musk',
        'Jeff bezos',
        'Lena Cain',
        'Lisa-Marie Mann',
        'Dwayne Magana',
        'Johnathan Deacon',
        'Brenden Barron',
        'Lily-Mai Prentice',
        'Terri Stevens',
        'Sommer Oneal',
        'Mackenzie Palacios',
        'Peter Green',
        'Cristian Baker',
        'Glen Williamson',
        'Nabeela Wilkinson',
        'Miruna Goodwin',
        'Hubert Farmer',
        'Bea Davenport',
        'Clare Parkinson',
        'Blair Guest',
        'Bilaal Stokes',
        'Haya Neale',
        'Vinnie Bridges',
        'Rizwan Benitez',
        'Rafael Weber',
        'Jorden Partridge',
        'Bryce Moore',
        'Adil Murphy',
        'Anum Coffey',
        'Kianna Rose',
        'Sian Cote',
        'Alby Burke',
        'Kalum Conley',
        'Johnny Hudson',
        'Daria Hartman',
        'Audrey Lozano',
        'Niko Snider',
        'Rajveer Wilcox',
        'Cormac Daugherty',
        'Aayan Nguyen',
        'Bentley Davey',
        'Loui Cox',
        'Franco Hester',
        'Jorja Mitchell',
        'Mercedes Lynch',
        'Nayan Woolley',
        'Ivie Day',
        'Lorelai Lam',
        'Hamza OMoore',
        'William Castro',
        'Karina Read',
        'Amandeep Hart',
        'Khushi Quintero',
        'Leonidas Osborn',
        'Katie Cairns',
        'Riccardo Norman',
        'Lucien Sanders',
        'Clara Ballard',
        'Trevor Ingram',
        'Woodrow Valdez',
        'Lina Mcdonnell',
        'Alec Hilton',
        'Rajan Petty',
        'Ameer Dickens',
        'Rodrigo Guzman',
        'Lola Tyson',
        'Malaikah Kelly',
        'Karishma Phillips',
        'Melanie Palmer',
        'Dustin Gibson',
        'Osama Clegg',
        'Justin Vaughn',
        'Caspar Patel',
        'Areeb Cleveland',
        'Tyreke Mullen',
        'Timur Mccall',
        'Yvie Rowland',
        'Anand Miranda',
        'Luc Alvarado',
        'Megan Nash',
        'Eleni Draper',
        'Christina Lancaster',
        'Charly Tang',
        'Awais Scott',
        'Ishaan Jefferson',
        'Olaf Lennon',
        'Saif Hendrix',
        'Sophia Reed',
        'Flora Hines',
        'Moesha Barnard',
        'Cheyenne Graves',
        'Virginia Mcdermott',
        'Francesca Mackenzie',
        'Louisa Galindo',
        'Kelan Bruce',
        'Conna Bradford',
        'Nada Sweet',
        'Neive Piper',
        'Julia Middleton',
        'Monique Waller',
        'Waleed Neville',
        'Emily-Jane Houston',
        'Fenton Randall',
        'Conner Gould',
        'Olaf Armstrong',
        'Izaac Whitley',
        'Imani Ryder',
        'Larry Reyna',
        'Jerome Wallace',
        'Jaye Burt',
        'Josh Armitage',
        'Rosemarie Mckeown',
        'Kaylem Harrison',
        'Malakai Conway',
        'Abul Bonner',
        'Reis Mendez',
        'Pedro Burton',
        'Brendan Dickens',
        'Kaidan Valdez',
        'Tevin Hawes',
        'Abdur Mckenna',
        'Charley Nava',
        'Moshe Bishop',
        'Tommy Orr',
        'Zayne Mohamed',
        'Logan Berg',
        'Haris Huang',
        'Cory Reilly',
        'Ramone Grainger',
        'Gurpreet Oneil',
        'Xander Gardner',
        'Vivek Fowler',
        'Rohaan Lucas',
        'Dafydd Gray',
        'Tolga Brock',
        'Aman Blair',
        'Jia Swift',
        'Yuvraj Begum',
        'Haydn Huff',
        'Parker Sweeney',
        'Patrik Maddox',
        'Amal Bernal',
        'Franco Person',
        'Stefano Buck',
        'Damian Pickett',
        'Maxwell Connelly',
        'Marley Stanley',
        'Pranav Houghton',
        'Seb Michael',
        'Antonio Ponce',
        'Amani Donovan',
        'Elliot Huffman',
        'Elisha Skinner',
        'Rayan Landry',
        'Albi Keller',
        'Jorge Moon',
        'Fabien Ballard',
        'Dylon Melendez',
        'Ehsan Salter',
        'Jozef Conrad',
        'Kacy White',
        'Arron Mueller',
        'Priscilla Pearce',
        'Riley Barry',
        'Leopold Senior',
        'Keanan Rooney',
        'Christie Payne',
        'Md Franks',
        'Warwick Olsen',
        'Tyriq Schmidt',
        'Fletcher Coulson',
        'Bhavik Hilton',
        'Austin Delacruz',
        'Tomas Churchill',
        'Berat Bauer',
        'Kyron Bright',
        'Amar Golden',
        'Drake Lugo',
        'Musa Childs',
        'Maheen Pritchard',
        'Johnny Henry',
        'Mae Major',
        'Christy Corrigan',
        'Nicolas Wiley',
        'Santiago Puckett',
        'Omar Oliver',
        'Cobie Wilkins',
        'Eduardo Holloway',
        'Julian Vu',
        'Myron Berger',
        'Layton Naylor',
        'Zakir Hawkins',
        'Etienne Sierra',
        'Haley Drummond',
        'Geraint Dowling',
        'Dennis Mclean',
        'Renzo Beech',
        'Hugo Li',
        'Mcauley Bonilla',
        'Mahdi Kidd',
        'Elias Prosser',
        'Ibrar Howarth',
    ];

    private const companies = [
        'LightSpeed VT',
        'Meta',
        'Trump tower',
        'Space-x',
        'Amazon',
        '01 Communique Laboratory Inc.',
        '10X Capital Venture Acquisition Corp. II Cl A',
        '10X Capital Venture Acquisition Corp. II Un',
        '10X Capital Venture Acquisition Corp. II Wt',
        '10X Capital Venture Acquisition Corp. III',
        '10x Genomics Inc.',
        '111 Inc. ADR',
        '12 Retech Corp.',
        '17 Education & Technology Group Inc. ADR',
        '180 Degree Capital Corp.',
        '180 Life Sciences Corp.',
        '180 Life Sciences Corp. Wt',
        '1-800-FLOWERS.COM Inc. Cl A',
        '1812 Brewing Co. Inc.',
        '1847 Goedeker Inc.',
        '1847 Goedeker Inc. Wt',
        '1847 Holdings LLC',
        '1895 Bancorp of Wisconsin Inc.',
        '1911 Gold Corp.',
        '1933 Industries Inc.',
        '1Life Healthcare Inc.',
        '1MAGE Software Inc.',
        '1pm Industries Inc.',
        '1st Capital Bancorp',
        '1st Colonial Bancorp Inc.',
        '1st Source Corp.',
        '1stdibs.com Inc.',
        '20/20 Gene Systems Inc.',
        '2020 Bulkers Ltd.',
        '22nd Century Group Inc.',
        '23andMe Holding Co.',
        '26 Capital Acquisition Corp.',
        '2seventy bio Inc.',
        '2U Inc.',
        '30DC Inc.',
        '360 DigiTech Inc. ADR',
        '361 Degrees International Ltd.',
        '36Kr Holdings Inc. ADR',
        '374Water Inc.',
        '3D Pioneer Systems Inc.',
        '3D Systems Corp.',
        '3DX Industries Inc.',
        '3i Group PLC',
        '3i Group PLC ADR',
        '3M Co.',
        '4 Less Group Inc.',
        '420 Property Management Inc.',
        '49 North Resources Inc.',
        '4Cable TV International Inc.',
        '4D Molecular Therapeutics Inc.',
        '4D pharma PLC ADR',
        '4D pharma PLC Wt',
        '4DS Memory Ltd.',
        '4Front Ventures Corp.',
        '5:01 Acquisition Corp.',
        '51job Inc. ADR',
        '5N Plus Inc.',
        '7 Acquisition Corp.',
        '7 Acquisition Corp. Cl A',
        '7 Acquisition Corp. Wt',
        '727 Communications Inc.',
        '79North Inc.',
        '7GC & Co. Holdings Inc.',
        '7GC & Co. Holdings Inc. Cl A',
        '7GC & Co. Holdings Inc. Wt',
        '808 Renewable Energy Corp.',
        '88 Energy Ltd.',
        '888 Holdings PLC',
        '89bio Inc.',
        '8i Acquisition 2 Corp.',
        '8i Acquisition 2 Corp.',
        '8i Acquisition 2 Corp. Rt',
        '8i Acquisition 2 Corp. Wt',
        '8X8 Inc.',
        '9 Meters Biopharma Inc.',
        '908 Devices Inc.',
        '9F Inc. ADR',
        'A Classified Ad Inc.',
        'A Clean Slate Inc.',
        'A.G.F. Management Ltd. Cl B NV',
        'A.I.S. Resources Ltd.',
        'a.k.a. Brands Holding Corp.',
        'A.M. Castle & Co.',
        'A.O. Smith Corp.',
        'A.P. Moeller-Maersk A/S ADR',
        'A.P. Moeller-Maersk A/S Series A',
        'A.P. Moeller-Maersk A/S Series B',
        'A-1 Group Inc.',
        'A10 Networks Inc.',
        'a2 Milk Co. Ltd.',
        'a2 Milk Co. Ltd. ADR',
        'A2A S.p.A.',
        'A2Z Smart Technologies Corp.',
        'AAB National Co.',
        'AAC Clyde Space AB',
        'AAC Technologies Holdings Inc.',
        'AAC Technologies Holdings Inc. ADR',
        'Aadi Bioscience Inc.',
        'Aalberts Industries N.V.',
        'AAON Inc.',
        'AAP Inc.',
        'AAR Corp.',
        'Aareal Bank AG',
        'Aareal Bank AG ADR',
        'Aarons Co. Inc.',
        'AB International Group Corp.',
        'AB&T Financial Corp.',
        'Abacus Mining & Exploration Corp.',
        'Abattis Bioceuticals Corp.',
        'Abaxx Technologies Inc.',
        'Abaxx Technologies Inc. Wt',
        'ABB Ltd.',
        'ABB Ltd. ADR',
        'Abbott Laboratories',
        'AbbVie Inc.',
        'Abcam PLC',
        'Abcam PLC ADR',
        'AbCellera Biologics Inc.',
        'ABCO Energy Inc.',
        'Abcourt Mines Inc.',
        'Aben Resources Ltd.',
        'Abeona Therapeutics Inc.',
        'Abercrombie & Fitch Co.',
        'Aberdeen Income Credit Strategies Fund 5.25% Perp. Pfd. Series A',
        'Aberdeen International Inc.',
        'ABG Acquisition Corp. I Cl A',
        'ABG Sundal Collier Holding ASA',
        'Abiomed Inc.',
        'Ablaze Technologies Inc.',
        'ABM Industries Inc.',
        'ABN AMRO Bank N.V. ADR',
        'Aboitiz Equity Ventures Inc. ADR',
        'Aboitiz Power Corp.',
        'Aboitiz Power Corp. ADR',
        'AbraSilver Resource Corp.',
        'Abraxas Petroleum Corp.',
        'abrdn PLC',
        'abrdn PLC ADR',
        'Abri SPAC I Inc.',
        'Abri SPAC I Inc.',
        'Abri SPAC I Inc. Wt',
        'Absa Group Ltd.',
        'Absci Corp.',
        'Absecon Bancorp',
        'Absolute Health & Fitness Inc.',
        'Absolute Software Corp.',
        'ABV Consulting Inc.',
        'ABVC BioPharma Inc.',
        'AC Immune S.A.',
        'Acacia Diversified Holdings Inc.',
        'Acacia Pharma Group PLC',
        'Acacia Research Corp. - Acacia Technologies',
        'Academy Sports & Outdoors Inc.',
        'Acadia Healthcare Co. Inc.',
        'ACADIA Pharmaceuticals Inc.',
        'Acadia Realty Trust',
        'Acadian Timber Corp.',
        'Acasti Pharma Inc.',
        'ACC Aviation Holdings Ltd.',
        'Accel Entertainment Inc.',
        'Accelerate Acquisition Corp.',
        'Accelerate Acquisition Corp. Cl A',
        'Accelerate Acquisition Corp. Wt',
        'Accelerate Diagnostics Inc.',
        'Accelerated Technologies Holding Corp.',
        'Acceleware Ltd.',
        'Accell Group N.V.',
        'Accenture PLC Cl A',
        'Access-Power & Co. Inc.',
        'Acciona S.A.',
        'ACCO Brands Corp.',
        'Accolade Inc.',
        'Accor S.A.',
        'Accor S.A. ADR',
        'Accord Financial Corp.',
        'Accretion Acquisition Corp.',
        'Accretion Acquisition Corp.',
        'Accretion Acquisition Corp. Rt',
        'Accretion Acquisition Corp. Wt',
        'Accsys Technologies PLC',
        'Accuray Inc.',
        'Accustem Sciences Ltd. ADR',
        'ACE Convergence Acquisition Corp. Cl A',
        'ACE Convergence Acquisition Corp. Un',
        'ACE Convergence Acquisition Corp. Wt',
        'Ace Global Business Acquisition Ltd.',
        'Ace Global Business Acquisition Ltd.',
        'Ace Global Business Acquisition Ltd. Wt',
        'Ace Hardware Indonesia',
        'Acea S.p.A.',
        'AcelRx Pharmaceuticals Inc.',
        'Acer Therapeutics Inc.',
        'Acerinox S.A. ADR',
        'Acerus Pharmaceuticals Corp.',
        'Achari Ventures Holdings Corp. I',
        'Achari Ventures Holdings Corp. I',
        'Achari Ventures Holdings Corp. I Wt',
        'Achieve Life Sciences Inc.',
        'Achilles Therapeutics PLC ADR',
        'Achison Inc.',
    ];

    private const birthdays = [
        '13/08/1987',
        '14/08/1987',
        '23/11/1995',
        '01/04/1957',
        '10/04/1947',
        '08/04/1948',
        '16/04/1948',
        '08/08/1955',
        '18/07/1957',
        '24/07/1957',
        '28/07/1957',
        '25/03/1983',
        '05/08/1987',
        '09/04/1948',
        '09/08/1955',
        '31/03/1957',
        '19/07/1957',
        '20/07/1957',
        '26/07/1957',
        '26/03/1983',
        '03/04/1983',
        '04/04/1983',
        '06/08/1987',
        '04/04/1946',
        '05/04/1946',
        '06/04/1946',
        '08/08/1987',
        '10/08/1987',
        '12/08/1987',
        '16/08/1987',
        '18/08/1987',
        '24/11/1995',
        '21/03/1957',
        '02/04/1983',
        '21/03/1947',
        '19/01/1956',
        '02/04/1957',
        '07/08/1987',
        '08/04/1996',
        '02/08/1988',
        '06/01/1940',
        '13/02/1940',
        '14/12/1945',
        '14/02/1947',
        '23/02/1947',
        '09/04/1947',
        '18/04/1947',
        '05/08/1947',
        '13/08/1947',
        '07/04/1946',
        '28/01/1949',
        '04/04/1948',
        '14/04/1952',
        '02/08/1955',
        '17/08/1955',
        '28/01/1956',
        '15/02/1956',
        '24/02/1956',
        '10/04/1956',
        '21/12/1956',
        '09/04/1957',
        '09/07/1957',
        '25/07/1957',
        '05/08/1957',
        '06/05/1958',
        '23/11/1958',
        '10/12/1958',
        '19/12/1959',
        '27/11/1963',
        '09/04/1968',
        '16/04/1968',
        '07/04/1969',
        '28/11/1971',
        '09/08/1972',
        '08/04/1946',
        '13/12/1974',
        '03/12/1975',
        '27/07/1976',
        '23/11/1976',
        '30/03/1977',
        '22/03/1978',
        '29/03/1978',
        '17/04/1978',
        '09/12/1978',
        '25/04/1979',
        '04/05/1979',
        '04/08/1979',
        '13/04/1981',
        '27/11/1982',
        '05/12/1982',
        '26/02/1983',
        '06/03/1983',
        '16/03/1983',
        '24/03/1983',
        '05/09/1983',
        '26/11/1983',
        '04/12/1983',
        '04/12/1985',
        '20/12/1985',
        '22/03/1986',
        '17/04/1987',
        '26/04/1987',
        '26/07/1987',
        '09/08/1987',
        '11/08/1987',
        '15/08/1987',
        '17/08/1987',
        '23/11/1987',
        '10/12/1987',
        '12/02/1988',
        '11/08/1988',
        '10/04/1995',
        '29/03/1997',
        '21/03/1998',
        '28/03/1998',
        '06/04/1998',
        '08/12/1998',
        '01/09/1998',
        '02/12/1958',
        '02/08/1987',
        '01/12/1976',
        '14/02/1940',
        '15/12/1945',
        '15/02/1947',
        '24/02/1947',
        '03/03/1947',
        '22/03/1947',
        '31/03/1947',
        '12/04/1947',
        '14/04/1947',
        '16/04/1947',
        '19/04/1947',
        '06/08/1947',
        '14/08/1947',
        '15/08/1947',
        '05/04/1948',
        '07/04/1948',
        '11/04/1948',
        '15/04/1948',
        '15/04/1952',
        '31/07/1955',
        '04/08/1955',
        '05/08/1955',
        '07/08/1955',
        '29/01/1949',
        '09/04/1946',
        '10/04/1946',
        '11/08/1955',
        '12/08/1955',
        '14/08/1955',
        '18/08/1955',
        '20/01/1956',
        '21/01/1956',
        '17/02/1956',
        '25/02/1956',
        '31/03/1956',
        '01/04/1956',
        '02/04/1956',
        '11/04/1956',
        '22/03/1957',
        '23/03/1957',
        '27/03/1957',
        '28/03/1957',
        '30/03/1957',
        '04/04/1957',
        '10/07/1957',
        '14/07/1957',
        '15/07/1957',
        '17/07/1957',
        '22/07/1957',
        '11/04/1946',
        '30/07/1957',
        '06/08/1957',
        '03/12/1958',
        '11/12/1958',
        '20/12/1959',
        '28/11/1963',
        '17/04/1968',
        '01/04/1969',
        '08/04/1969',
        '29/11/1971',
        '31/07/1972',
        '10/08/1972',
        '04/12/1975',
        '28/07/1976',
        '03/12/1976',
        '22/03/1977',
        '31/03/1977',
        '01/04/1977',
        '30/03/1978',
        '18/04/1978',
        '30/11/1978',
        '10/12/1978',
        '12/04/1946',
        '13/04/1946',
        '14/04/1946',
        '26/04/1979',
        '05/05/1979',
        '05/08/1979',
        '14/04/1981',
        '28/11/1982',
        '06/12/1982',
        '07/03/1983',
        '17/03/1983',
        '28/03/1983',
        '30/03/1983'
    ];

    public function __construct(private UserRepository $userRepository)
    {
    }

    private function loadOwners(ObjectManager $manager) {
        $userid = 'f2881cd4-a02e-48dc-81bf-f1537a0b903f';
        $michalUserId = 'c7e2a147-8234-4af7-8c22-b686e5ba1e8a';

        $user = new User();
        $user->profile = new UserProfile();
        $contactProfile = new ContactProfile();
        $companyInfo = new ContactCompany();


        $user
            ->setUserId($userid)
            ->setEmail('levan.ostrowski@gmail.com')
            ->setPassword(password_hash('admin', PASSWORD_DEFAULT))
            ->setRoles([User::ROLE_USER, User::ROLE_ADMIN])
            ->setLastLoginAt()
            ->setCreatedAt();

        $user->profile
            ->setUserId($userid)
            ->setFirstName('Levan')
            ->setLastName('Ostrowski')
            ->setBirthDay(new DateTime('1998-11-17'))
            ->setEmail('levan.ostrowski@gmail.com')
            ->setCreatedAt();;

        $contactProfile
            ->setOwnerUserId($user->getUserId())
            ->setFirstName('Levan')
            ->setLastName('Ostrowski')
            ->setBirthDay(new DateTime('1998-11-17'))
            ->setCountry('Gruzja')
            ->setCreatedAt();

        $companyInfo
            ->setCompanyName('Roots-Connector')
            ->setCompanyWww('www.rootsconnector.com')
            ->setCompanyIndustry('IT')
            ->setWayToEarnMoney('Want more? do more !!!')
            ->setRegon('12345')
            ->setKrs('54321')
            ->setNip('56789')
            ->setDistricts('LA, Birmingham')
            ->setHeadQuartersCity('England ....')
            ->setBusinessEmails('bussiness@rootsconnector.com')
            ->setBusinessPhones(
                '514380928, '.
                '514380929, '.
                '514380930, '.
                '514380931'
            )
            ->setRevenue('10000000')
            ->setProfit('5000000')
            ->setGrowthYearToYear('')
            ->setCategories('IT, Programming, Big Data, AI, Cyber Security')
            ->setCreatedAt();

        $contactProfile->setContactCompany($companyInfo);
        $user->addContact($contactProfile);

        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->profile = new UserProfile();
        $contactProfile = new ContactProfile();
        $companyInfo = new ContactCompany();
        $user
            ->setUserId($michalUserId)
            ->setEmail('michalwojda@gmail.com')
            ->setPassword(password_hash('admin', PASSWORD_DEFAULT))
            ->setRoles([User::ROLE_USER, User::ROLE_ADMIN])
            ->setLastLoginAt()
            ->setCreatedAt();

        $user->profile
            ->setUserId($michalUserId)
            ->setFirstName('Michał')
            ->setLastName('Wojda')
            ->setBirthDay(new DateTime('1998-11-17'))
            ->setEmail('michalwojda@gmail.com')
            ->setCreatedAt();;


        $contactProfile
            ->setOwner($user)
            ->setOwnerUserId($user->getUserId())
            ->setFirstName('Michał')
            ->setLastName('Wojda')
            ->setBirthDay(new DateTime('1998-11-17'))
            ->setCountry('Polska')
            ->setCreatedAt();

        $companyInfo
            ->setCompanyName('Roots-Connector')
            ->setCompanyWww('www.rootsconnector.com')
            ->setCompanyIndustry('IT')
            ->setWayToEarnMoney('Want more? do more !!!')
            ->setRegon('12345')
            ->setKrs('54321')
            ->setNip('56789')
            ->setDistricts('LA, Birmingham')
            ->setHeadQuartersCity('England ....')
            ->setBusinessEmails('bussiness@rootsconnector.com')
            ->setBusinessPhones(
                '514380928, '.
                '514380929, '.
                '514380930, '.
                '514380931'
            )
            ->setRevenue('10000000')
            ->setProfit('5000000')
            ->setGrowthYearToYear('')
            ->setCategories('IT, Programming, Big Data, AI, Cyber Security')
            ->setCreatedAt();

        $contactProfile->setContactCompany($companyInfo);
        $user->addContact($contactProfile);

        $manager->persist($user);
        $manager->flush();
    }
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadOwners($manager);
        $owner = $this->userRepository->findByEmail('levan.ostrowski@gmail.com');//me

        for ($iter = 0 ; $iter < 2; $iter ++) {

            if ($iter === 1) {
                $owner = $this->userRepository->findByEmail('michalwojda@gmail.com');; //michał
            }
            for($i = 0; $i < count(self::emails); $i++)
            {
                $email = self::emails[$i];
                $name = explode(' ', self::names[$i])[0];
                $lastname = explode(' ', self::names[$i])[1];
                $company = self::companies[$i];

                $birthDayParts = explode('/', self::birthdays[$i]);
                list($day, $month, $year) = $birthDayParts;

                $birthDayStr = $year . '/'. $month . '/' . $day;
                $birthDay = DateTime::createFromFormat('Y/m/d', $birthDayStr);

                if (gettype($birthDay) === 'boolean') {
                    $birthDay = new DateTime();
                }


                $contactProfile = new ContactProfile();
                $companyInfo = new ContactCompany();

                $contactProfile
                    ->genContactId()
                    ->setOwner($owner)
                    ->setOwnerUserId($owner->getUserId())
                    ->setFirstName($name)
                    ->setLastName($lastname)
                    ->setEmail($email)
                    ->setBirthDay(new DateTime('1998-11-17'))
                    ->setCountry('Polska')
                    ->setCreatedAt();


                $companyInfo
                    ->setCompanyName($company)
                    ->setCompanyWww('www.'.str_replace(' ', '-', $company).'.com')
                    ->setCompanyIndustry('IT')
                    ->setWayToEarnMoney('Want more? do more !!!')
                    ->setRegon('12345')
                    ->setKrs('54321')
                    ->setNip('56789')
                    ->setDistricts('LA')
                    ->setHeadQuartersCity('USA ....')
                    ->setBusinessEmails('bussiness@'.str_replace(' ', '-', $company).'.com')
                    ->setBusinessPhones(
                        '51438092,'.$i.
                        ', 51438092'.$i.
                        ', 51438092'.$i.
                        ', 51438092'.$i
                    )
                    ->setRevenue('10000000')
                    ->setProfit('5000000')
                    ->setGrowthYearToYear('')
                    ->setCategories('IT, Programming, Big Data, AI, Cyber Security')
                    ->setCreatedAt();

                $contactProfile->setContactCompany($companyInfo);
                $owner->addContact($contactProfile);

                $manager->persist($contactProfile);
                $manager->flush();
            }
        }
    }
}
