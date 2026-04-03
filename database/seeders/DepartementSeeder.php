<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartementSeeder extends Seeder
{
    public function run(): void
    {
        // Récupération des pays par nom
        $regions = DB::table('regions')->pluck('id', 'nom');

        $departements = [

            // =========================================================================
            // BÉNIN
            // =========================================================================
            'Alibori' => [
                ['nom' => 'Banikoara'], ['nom' => 'Gogounou'], ['nom' => 'Kandi'], 
                ['nom' => 'Karimama'], ['nom' => 'Malanville'], ['nom' => 'Segbana'],
            ],
            'Atacora' => [
                ['nom' => 'Boukoumbé'], ['nom' => 'Cobly'], ['nom' => 'Kérou'], ['nom' => 'Kouandé'], 
                ['nom' => 'Matéri'], ['nom' => 'Natitingou'], ['nom' => 'Pehunco'], ['nom' => 'Tanguiéta'], 
                ['nom' => 'Toucountouna'],
            ],
            'Atlantique' => [
                ['nom' => 'Abomey-Calavi'], ['nom' => 'Allada'], ['nom' => 'Kpomassé'], ['nom' => 'Ouidah'], 
                ['nom' => 'Sô-Ava'], ['nom' => 'Toffo'], ['nom' => 'Tori-Bossito'], ['nom' => 'Zè'],
            ],
            'Borgou' => [
                ['nom' => 'Bembéréké'], ['nom' => 'Kalalé'], ['nom' => 'N\'Dali'], ['nom' => 'Nikki'], 
                ['nom' => 'Parakou'], ['nom' => 'Pèrèrè'], ['nom' => 'Sinendé'], ['nom' => 'Tchaourou'],
            ],
            'Collines' => [
                ['nom' => 'Bantè'], ['nom' => 'Dassa-Zoumè'], ['nom' => 'Glazoué'], ['nom' => 'Ouèssè'], 
                ['nom' => 'Savalou'], ['nom' => 'Savè'],
            ],
            'Couffo' => [
                ['nom' => 'Aplahoué'], ['nom' => 'Djakotomey'], ['nom' => 'Dogbo-Tota'], 
                ['nom' => 'Klouékanmè'], ['nom' => 'Lalo'], ['nom' => 'Toviklin'],
            ],
            'Donga' => [
                ['nom' => 'Bassila'], ['nom' => 'Copargo'], ['nom' => 'Djougou'], ['nom' => 'Ouaké'],
            ],
            'Littoral' => [['nom' => 'Cotonou']],
            'Mono' => [
                ['nom' => 'Athiémé'], ['nom' => 'Bopa'], ['nom' => 'Comè'], ['nom' => 'Grand-Popo'], 
                ['nom' => 'Houéyogbé'], ['nom' => 'Lokossa'],
            ],
            'Ouémé' => [
                ['nom' => 'Adjarra'], ['nom' => 'Adjohoun'], ['nom' => 'Aguégués'], ['nom' => 'Akpro-Missérété'], 
                ['nom' => 'Avrankou'], ['nom' => 'Bonou'], ['nom' => 'Dangbo'], ['nom' => 'Porto-Novo'], ['nom' => 'Sèmè-Kpodji'],
            ],
            'Plateau' => [
                ['nom' => 'Adja-Ouèrè'], ['nom' => 'Ifangni'], ['nom' => 'Kétou'], ['nom' => 'Pobè'], ['nom' => 'Sakété'],
            ],
            'Zou' => [
                ['nom' => 'Abomey'], ['nom' => 'Agbangnizoun'], ['nom' => 'Bohicon'], ['nom' => 'Covè'], 
                ['nom' => 'Djidja'], ['nom' => 'Ouinhi'], ['nom' => 'Za-Kpota'], ['nom' => 'Zagnanado'], ['nom' => 'Zogbodomey'],
            ],

            // =========================================================================
            // BURKINA FASO
            // =========================================================================
            'Bankui' => [['nom' => 'Balé'], ['nom' => 'Banwa'], ['nom' => 'Kossi'], ['nom' => 'Mouhoun'], ['nom' => 'Soli']],
            'Sourou' => [['nom' => 'Nayala'], ['nom' => 'Sourou']],
            'Kadiogo' => [['nom' => 'Kadiogo']],
            'Nakambé' => [['nom' => 'Bam'], ['nom' => 'Namentenga'], ['nom' => 'Sanmatenga']],
            'Moogo' => [['nom' => 'Boulgou'], ['nom' => 'Koulpélogo'], ['nom' => 'Kouritenga']],
            'Bulkiemdé' => [['nom' => 'Boulkiemdé'], ['nom' => 'Sanguié'], ['nom' => 'Sissili'], ['nom' => 'Ziro']],
            'Zoundwéogo' => [['nom' => 'Bazèga'], ['nom' => 'Nahouri'], ['nom' => 'Zoundwéogo']],
            'Comoé' => [['nom' => 'Comoé'], ['nom' => 'Léraba']],
            'Guiriko' => [['nom' => 'Houet'], ['nom' => 'Kénédougou'], ['nom' => 'Tuy']],
            'Gourma' => [['nom' => 'Gourma'], ['nom' => 'Kompienga']],
            'Sirba' => [['nom' => 'Gnagna'], ['nom' => 'Komandjari']],
            'Tapoa' => [['nom' => 'Tapoa I'], ['nom' => 'Tapoa II']],
            'Yatenga' => [['nom' => 'Loroum'], ['nom' => 'Passoré'], ['nom' => 'Yatenga'], ['nom' => 'Zondoma']],
            'Oubritenga' => [['nom' => 'Ganzourgou'], ['nom' => 'Kourwéogo'], ['nom' => 'Oubritenga']],
            'Liptako' => [['nom' => 'Oudalan'], ['nom' => 'Séno'], ['nom' => 'Yagha']],
            'Sum' => [['nom' => 'Soum']],
            'Djôrô' => [['nom' => 'Bougouriba'], ['nom' => 'Ioba'], ['nom' => 'Noumbiel'], ['nom' => 'Poni']],

            // =========================================================================
            // CÔTE D'IVOIRE
            // =========================================================================
            'Abidjan' => [['nom' => 'Abidjan']],
            'Yamoussoukro' => [['nom' => 'Yamoussoukro']],
            'Bas-Sassandra' => [['nom' => 'Gboklè'], ['nom' => 'Nava'], ['nom' => 'San-Pédro']],
            'Comoé' => [['nom' => 'Indénié-Djuablin'], ['nom' => 'Sud-Comoé']],
            'Denguélé' => [['nom' => 'Folon'], ['nom' => 'Kabadougou']],
            'Gôh-Djiboua' => [['nom' => 'Gôh'], ['nom' => 'Lôh-Djiboua']],
            'Lacs' => [['nom' => 'Bélier'], ['nom' => 'Iffou'], ['nom' => 'Moronou'], ['nom' => 'N\'Zi']],
            'Lagunes' => [['nom' => 'Agnéby-Tiassa'], ['nom' => 'Grands-Ponts'], ['nom' => 'La Mé']],
            'Montagnes' => [['nom' => 'Cavally'], ['nom' => 'Guémon'], ['nom' => 'Tonkpi']],
            'Sassandra-Marahoué' => [['nom' => 'Haut-Sassandra'], ['nom' => 'Marahoué']],
            'Savanes' => [['nom' => 'Bagoué'], ['nom' => 'Poro'], ['nom' => 'Tchologo']],
            'Vallée du Bandama' => [['nom' => 'Gbêkê'], ['nom' => 'Hambol']],
            'Woroba' => [['nom' => 'Béré'], ['nom' => 'Bafing'], ['nom' => 'Worodougou']],
            'Zanzan' => [['nom' => 'Bounkani'], ['nom' => 'Gontougo']],

            // =========================================================================
            // GUINÉE BISSAU
            // =========================================================================
            'Bissau' => [['nom' => 'Bissau']],
            'Biombo' => [['nom' => 'Prabis'], ['nom' => 'Quinhámel'], ['nom' => 'Safim']],
            'Cacheu' => [['nom' => 'Bigene'], ['nom' => 'Bula'], ['nom' => 'Cacheu'], ['nom' => 'Caió'], ['nom' => 'Canchungo'], ['nom' => 'São Domingos']],
            'Oio' => [['nom' => 'Bissorã'], ['nom' => 'Farim'], ['nom' => 'Mansaba'], ['nom' => 'Mansôa'], ['nom' => 'Nhacra']],
            'Bafatá' => [['nom' => 'Bafatá'], ['nom' => 'Bambadinca'], ['nom' => 'Contuboel'], ['nom' => 'Galomaro'], ['nom' => 'Gã-Mamudo'], ['nom' => 'Xitole']],
            'Gabú' => [['nom' => 'Madina do Boé'], ['nom' => 'Gabú'], ['nom' => 'Pirada'], ['nom' => 'Pitche'], ['nom' => 'Sonaco']],
            'Bolama-Bijagós' => [['nom' => 'Bolama'], ['nom' => 'Bubaque'], ['nom' => 'Caravela'], ['nom' => 'Uno']],
            'Quínara' => [['nom' => 'Buba'], ['nom' => 'Empada'], ['nom' => 'Fulacunda'], ['nom' => 'Tite']],
            'Tombali' => [['nom' => 'Bedanda'], ['nom' => 'Cacine'], ['nom' => 'Catió'], ['nom' => 'Komo'], ['nom' => 'Quebo']],

            // =========================================================================
            // MALI
            // =========================================================================
            'Kayes' => [['nom' => 'Kayes'], ['nom' => 'Bafoulabé'], ['nom' => 'Kéniéba'], ['nom' => 'Yélimané'], ['nom' => 'Sadiola'], ['nom' => 'Diamou'], ['nom' => 'Oussoubidiagna'], ['nom' => 'Séféto'], ['nom' => 'Ambidédi'], ['nom' => 'Aourou']],
            'Koulikoro' => [['nom' => 'Koulikoro'], ['nom' => 'Banamba'], ['nom' => 'Kangaba'], ['nom' => 'Kolokani'], ['nom' => 'Kati'], ['nom' => 'Siby'], ['nom' => 'Néguela'], ['nom' => 'Ouelessebougou'], ['nom' => 'Kalabancoro']],
            'Sikasso' => [['nom' => 'Sikasso'], ['nom' => 'Kadiolo'], ['nom' => 'Niéna'], ['nom' => 'Danderesso'], ['nom' => 'Kléla'], ['nom' => 'Lobougoula'], ['nom' => 'Loulouni'], ['nom' => 'Finkolo']],
            'Ségou' => [['nom' => 'Ségou'], ['nom' => 'Bla'], ['nom' => 'Macina'], ['nom' => 'Niono'], ['nom' => 'Barouéli'], ['nom' => 'Farako'], ['nom' => 'Dioro'], ['nom' => 'Saminé'], ['nom' => 'Sansanding'], ['nom' => 'Sibila']],
            'Mopti' => [['nom' => 'Mopti'], ['nom' => 'Djenné'], ['nom' => 'Ténenkou'], ['nom' => 'Youwarou'], ['nom' => 'Konna'], ['nom' => 'Barbé'], ['nom' => 'Fatoma'], ['nom' => 'Ouroubé-Doundé']],
            'Tombouctou' => [['nom' => 'Tombouctou'], ['nom' => 'Diré'], ['nom' => 'Goundam'], ['nom' => 'Tonka'], ['nom' => 'Ber'], ['nom' => 'Gourma-Rharous'], ['nom' => 'Bambara-Maoudé'], ['nom' => 'Léré'], ['nom' => 'Niafounké'], ['nom' => 'Saréyamou']],
            'Gao' => [['nom' => 'Gao'], ['nom' => 'Ansongo'], ['nom' => 'Bourem'], ['nom' => 'Djébock'], ['nom' => 'Talataye'], ['nom' => 'Bamba'], ['nom' => 'N\'Tillit'], ['nom' => 'Gabéro'], ['nom' => 'Tessit'], ['nom' => 'Haoussa-Foulane'], ['nom' => 'Bourra'], ['nom' => 'Ouatagouna'], ['nom' => 'Sony Aliber'], ['nom' => 'Gounzoureye'], ['nom' => 'Anchawadj'], ['nom' => 'Tilemsi'], ['nom' => 'Tarkint']],
            'Kidal' => [['nom' => 'Kidal'], ['nom' => 'Tin-Essako'], ['nom' => 'Achibogho'], ['nom' => 'Timétrine'], ['nom' => 'Takalote'], ['nom' => 'Tessalit'], ['nom' => 'Aguelhoc'], ['nom' => 'Anéfif']],
            'Taoudénit' => [['nom' => 'Taoudénit'], ['nom' => 'Foum-Alba'], ['nom' => 'Araouane'], ['nom' => 'Boû-Djébéha'], ['nom' => 'Achouratt'], ['nom' => 'Al-Ourche']],
            'Ménaka' => [['nom' => 'Ménaka'], ['nom' => 'Andéramboukane'], ['nom' => 'Inékar'], ['nom' => 'Tidermène'], ['nom' => 'Baki-Sarat'], ['nom' => 'Sahert']],
            'Nioro' => [['nom' => 'Nioro'], ['nom' => 'Diéma'], ['nom' => 'Troungoumbé'], ['nom' => 'Youri'], ['nom' => 'Sandaré'], ['nom' => 'Lakamané'], ['nom' => 'Béma'], ['nom' => 'Diangounté Camara'], ['nom' => 'Simbi']],
            'Kita' => [['nom' => 'Kita'], ['nom' => 'Sagabari'], ['nom' => 'Toukoto'], ['nom' => 'Séféto'], ['nom' => 'Sirakoro'], ['nom' => 'Boudofo'], ['nom' => 'Kokofata'], ['nom' => 'Sébékoró']],
            'Dioïla' => [['nom' => 'Dioïla'], ['nom' => 'Banco'], ['nom' => 'Fana'], ['nom' => 'Ména'], ['nom' => 'Massigui'], ['nom' => 'Béléko'], ['nom' => 'Diébé'], ['nom' => 'Dolendougou'], ['nom' => 'N\'Golobougou']],
            'Nara' => [['nom' => 'Nara'], ['nom' => 'Ballé'], ['nom' => 'Dilly'], ['nom' => 'Mourdiah'], ['nom' => 'Guiré'], ['nom' => 'Fallou'], ['nom' => 'Central'], ['nom' => 'Kaloumba'], ['nom' => 'Ouagadou']],
            'Bougouni' => [['nom' => 'Bougouni'], ['nom' => 'Yanfolila'], ['nom' => 'Kolondiéba'], ['nom' => 'Garalo'], ['nom' => 'Koumantou'], ['nom' => 'Sélingué'], ['nom' => 'Ouélessébougou'], ['nom' => 'Kadiana'], ['nom' => 'Fakola'], ['nom' => 'Dogo'], ['nom' => 'Yinindougou']],
            'Koutiala' => [['nom' => 'Koutiala'], ['nom' => 'Yorosso'], ['nom' => 'M\'Pessoba'], ['nom' => 'Molobala'], ['nom' => 'Koury'], ['nom' => 'Konséguéla'], ['nom' => 'Kouniana'], ['nom' => 'Zangasso'], ['nom' => 'Sinkolo'], ['nom' => 'Niantasso']],
            'San' => [['nom' => 'San'], ['nom' => 'Tominian'], ['nom' => 'Yangasso'], ['nom' => 'Sy'], ['nom' => 'Fangasso'], ['nom' => 'Diéli'], ['nom' => 'Kimparana'], ['nom' => 'Téné'], ['nom' => 'Koula']],
            'Douentza' => [['nom' => 'Douentza'], ['nom' => 'Mondoro'], ['nom' => 'Haïré'], ['nom' => 'Boni'], ['nom' => 'Dianwély'], ['nom' => 'Hombori'], ['nom' => 'Ngouma'], ['nom' => 'Bore'], ['nom' => 'Dangol-Boré']],
            'Bandiagara' => [['nom' => 'Bandiagara'], ['nom' => 'Koro'], ['nom' => 'Bankass'], ['nom' => 'Sangha'], ['nom' => 'Ningari'], ['nom' => 'Diallassagou'], ['nom' => 'Baye'], ['nom' => 'Kendé'], ['nom' => 'Ouo'], ['nom' => 'Sokoura'], ['nom' => 'Segué']],
            'Bamako' => [['nom' => 'Arrondissement I'], ['nom' => 'Arrondissement II'], ['nom' => 'Arrondissement III'], ['nom' => 'Arrondissement IV'], ['nom' => 'Arrondissement V'], ['nom' => 'Arrondissement VI'], ['nom' => 'Arrondissement VII']],

            // =========================================================================
            // NIGER
            // =========================================================================
            'Agadez' => [['nom' => 'Aderbissinat'], ['nom' => 'Arlit'], ['nom' => 'Bilma'], ['nom' => 'Iférouane'], ['nom' => 'Ingal'], ['nom' => 'Tchirozérine']],
            'Diffa' => [['nom' => 'Bosso'], ['nom' => 'Diffa'], ['nom' => 'Goudoumaria'], ['nom' => 'Maïné-Soroa'], ['nom' => 'N\'Gourti'], ['nom' => 'N\'Guigmi']],
            'Dosso' => [['nom' => 'Boboye'], ['nom' => 'Dioundiou'], ['nom' => 'Dogondoutchi'], ['nom' => 'Dosso'], ['nom' => 'Falmey'], ['nom' => 'Gaya'], ['nom' => 'Loga'], ['nom' => 'Tibiri']],
            'Maradi' => [['nom' => 'Aguié'], ['nom' => 'Bermo'], ['nom' => 'Dakoro'], ['nom' => 'Gazoua'], ['nom' => 'Guidan-Roumdji'], ['nom' => 'Madarounfa'], ['nom' => 'Mayahi'], ['nom' => 'Tessaoua']],
            'Tahoua' => [['nom' => 'Abalak'], ['nom' => 'Bagaroua'], ['nom' => 'Birni-N\'Konni'], ['nom' => 'Bouza'], ['nom' => 'Illéla'], ['nom' => 'Keita'], ['nom' => 'Madaoua'], ['nom' => 'Malbaza'], ['nom' => 'Tahoua'], ['nom' => 'Tassara'], ['nom' => 'Tchintabaraden'], ['nom' => 'Tillia']],
            'Tillabéri' => [['nom' => 'Abala'], ['nom' => 'Ayorou'], ['nom' => 'Balleyara'], ['nom' => 'Banibangou'], ['nom' => 'Bankilaré'], ['nom' => 'Filingué'], ['nom' => 'Gothèye'], ['nom' => 'Kollo'], ['nom' => 'Ouallam'], ['nom' => 'Say'], ['nom' => 'Téra'], ['nom' => 'Tillabéri'], ['nom' => 'Torodi']],
            'Zinder' => [['nom' => 'Belbéji'], ['nom' => 'Damagaram Takaya'], ['nom' => 'Dungass'], ['nom' => 'Gourre'], ['nom' => 'Kantché'], ['nom' => 'Magaria'], ['nom' => 'Mirriah'], ['nom' => 'Takiéta'], ['nom' => 'Tanout'], ['nom' => 'Tesker']],
            'Niamey' => [['nom' => 'Arrondissement 1'], ['nom' => 'Arrondissement 2'], ['nom' => 'Arrondissement 3'], ['nom' => 'Arrondissement 4'], ['nom' => 'Arrondissement 5']],

            // =========================================================================
            // SÉNÉGAL
            // =========================================================================
            'Dakar' => [['nom' => 'Dakar'], ['nom' => 'Guédiawaye'], ['nom' => 'Keur Massar'], ['nom' => 'Pikine'], ['nom' => 'Rufisque']],
            'Diourbel' => [['nom' => 'Bambey'], ['nom' => 'Diourbel'], ['nom' => 'Mbacké']],
            'Fatick' => [['nom' => 'Fatick'], ['nom' => 'Foundiougne'], ['nom' => 'Gossas']],
            'Kaffrine' => [['nom' => 'Birkelane'], ['nom' => 'Kaffrine'], ['nom' => 'Koungheul'], ['nom' => 'Malem Hodar']],
            'Kaolack' => [['nom' => 'Guinguinéo'], ['nom' => 'Kaolack'], ['nom' => 'Nioro du Rip']],
            'Kédougou' => [['nom' => 'Kédougou'], ['nom' => 'Salémata'], ['nom' => 'Saraya']],
            'Kolda' => [['nom' => 'Kolda'], ['nom' => 'Médina Yoro Foulah'], ['nom' => 'Vélingara']],
            'Louga' => [['nom' => 'Kébémer'], ['nom' => 'Linguère'], ['nom' => 'Louga']],
            'Matam' => [['nom' => 'Kanel'], ['nom' => 'Matam'], ['nom' => 'Ranérou Ferlo']],
            'Saint-Louis' => [['nom' => 'Dagana'], ['nom' => 'Podor'], ['nom' => 'Saint-Louis']],
            'Sédhiou' => [['nom' => 'Bounkiling'], ['nom' => 'Goudomp'], ['nom' => 'Sédhiou']],
            'Tambacounda' => [['nom' => 'Bakel'], ['nom' => 'Goudiry'], ['nom' => 'Koumpentoum'], ['nom' => 'Tambacounda']],
            'Thiès' => [['nom' => 'Mbour'], ['nom' => 'Thiès'], ['nom' => 'Tivaouane']],
            'Ziguinchor' => [['nom' => 'Bignona'], ['nom' => 'Oussouye'], ['nom' => 'Ziguinchor']],

            // =========================================================================
            // TOGO
            // =========================================================================
            'Maritime' => [
                ['nom' => 'Avé'], ['nom' => 'Golfe'], ['nom' => 'Lacs'], ['nom' => 'Vo'],
                ['nom' => 'Yoto'], ['nom' => 'Zio'], ['nom' => 'Agoè-Nyivé'], ['nom' => 'Bas-Mono'],
            ],
            'Plateaux' => [
                ['nom' => 'Agou'], ['nom' => 'Amou'], ['nom' => 'Danyi'], ['nom' => 'Est-Mono'],
                ['nom' => 'Haho'], ['nom' => 'Kloto'], ['nom' => 'Moyen-Mono'], ['nom' => 'Ogou'],
                ['nom' => 'Wawa'], ['nom' => 'Akébou'], ['nom' => 'Anié'], ['nom' => 'Kpélé'],
            ],
            'Centrale' => [
                ['nom' => 'Blitta'], ['nom' => 'Sotouboua'], ['nom' => 'Tchamba'], 
                ['nom' => 'Tchaoudjo'], ['nom' => 'Mô'],
            ],
            'Kara' => [
                ['nom' => 'Assoli'], ['nom' => 'Bassar'], ['nom' => 'Binah'], ['nom' => 'Dankpen'],
                ['nom' => 'Doufelgou'], ['nom' => 'Kéran'], ['nom' => 'Kozah'],
            ],
            'Savanes' => [
                ['nom' => 'Kpendjal'], ['nom' => 'Oti'], ['nom' => 'Tandjouaré'], ['nom' => 'Tône'],
                ['nom' => 'Cinkassé'], ['nom' => 'Oti-Sud'], ['nom' => 'Kpendjal-Ouest'],
            ],
        ];

        foreach ($departements as $nomRegion => $listeDepartements) {

            if (!isset($regions[$nomRegion])) {
                continue;
            }

            foreach ($listeDepartements as $departement) {
                DB::table('departements')->insert([
                    'region_id' => $regions[$nomRegion],
                    'nom' => $departement['nom'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
