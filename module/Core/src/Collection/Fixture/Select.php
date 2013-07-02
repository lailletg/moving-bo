<?php

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class Select implements FixtureInterface
{
	
	public function load(ObjectManager $manager)
	{


		/* ************************************ *
		 * Ajout d'un Select Liste des pays     *
		 * ************************************ */


		$liste_des_pays = array("Afghanistan","Afrique du Sud","Albanie","Algérie","Allemagne","Andorre","Angola","Anguilla","Antarctique","Antigua-et-Barbuda","Antilles néerlandaises","Arabie saoudite","Argentine","Arménie","Aruba","Australie","Autriche","Azerbaïdjan","Bénin","Bahamas","Bahreïn","Bangladesh","Barbade","Belau","Belgique","Belize","Bermudes","Bhoutan","Biélorussie","Birmanie","Bolivie","Bosnie-Herzégovine","Botswana","Brésil","Brunei","Bulgarie","Burkina Faso","Burundi","Côte d'Ivoire","Cambodge","Cameroun","Canada","Cap-Vert","Chili","Chine","Chypre","Colombie","Comores","Congo","Corée du Nord","Corée du Sud","Costa Rica","Croatie","Cuba","Danemark","Djibouti","Dominique","Égypte","Émirats arabes unis","Équateur","Érythrée","Espagne","Estonie","États-Unis","Éthiopie","Finlande","France","Géorgie","Gabon","Gambie","Ghana","Gibraltar","Grèce","Grenade","Groenland","Guadeloupe","Guam","Guatemala","Guinée","Guinée équatoriale","Guinée-Bissao","Guyana","Guyane française","Haïti","Honduras","Hong Kong","Hongrie","Ile Bouvet","Ile Christmas","Ile Norfolk","Iles Cayman","Iles Cook","Iles Féroé","Iles Falkland","Iles Fidji","Iles Géorgie du Sud et Sandwich du Sud","Iles Heard et McDonald","Iles Marshall","Iles Pitcairn","Iles Salomon","Iles Svalbard et Jan Mayen","Iles Turks-et-Caicos","Iles Vierges américaines","Iles Vierges britanniques","Iles des Cocos (Keeling)","Iles mineures éloignées des États-Unis","Inde","Indonésie","Iran","Iraq","Irlande","Islande","Israël","Italie","Jamaïque","Japon","Jordanie","Kazakhstan","Kenya","Kirghizistan","Kiribati","Koweït","Laos","Lesotho","Lettonie","Liban","Liberia","Libye","Liechtenstein","Lituanie","Luxembourg","Macao","Madagascar","Malaisie","Malawi","Maldives","Mali","Malte","Mariannes du Nord","Maroc","Martinique","Maurice","Mauritanie","Mayotte","Mexique","Micronésie","Moldavie","Monaco","Mongolie","Montserrat","Mozambique","Népal","Namibie","Nauru","Nicaragua","Niger","Nigeria","Nioué","Norvège","Nouvelle-Calédonie","Nouvelle-Zélande","Oman","Ouganda","Ouzbékistan","Pérou","Pakistan","Panama","Papouasie-Nouvelle-Guinée","Paraguay","Pays-Bas","Philippines","Pologne","Polynésie française","Porto Rico","Portugal","Qatar","République centrafricaine","République démocratique du Congo","République dominicaine","République tchèque","Réunion","Roumanie","Royaume-Uni","Russie","Rwanda","Sénégal","Sahara occidental","Saint-Christophe-et-Niévès","Saint-Marin","Saint-Pierre-et-Miquelon","Saint-Siège","Saint-Vincent-et-les-Grenadines","Sainte-Hélène","Sainte-Lucie","Salvador","Samoa","Samoa américaines","Sao Tomé-et-Principe","Seychelles","Sierra Leone","Singapour","Slovénie","Slovaquie","Somalie","Soudan","Sri Lanka","Suède","Suisse","Suriname","Swaziland","Syrie","Taïwan","Tadjikistan","Tanzanie","Tchad","Terres australes françaises","Territoire britannique de l'Océan Indien","Thaïlande","Timor Oriental","Togo","Tokélaou","Tonga","Trinité-et-Tobago","Tunisie","Turkménistan","Turquie","Tuvalu","Ukraine","Uruguay","Vanuatu","Venezuela","Viêt Nam","Wallis-et-Futuna","Yémen","Yougoslavie","Zambie","Zimbabwe");

		$select = new Collection\Entity\Select();
		$select->__set('description', 'Liste des pays du monde');
		$select->__set('label', 'Pays');

		foreach ($liste_des_pays as $key => $pays) {
			$select_option =  new Collection\Entity\SelectOption($select);
			$select_option->__set('text',$pays);
			$manager->persist($select_option);

		}
		

		$manager->persist($select);

		$manager->flush();
	}
}
