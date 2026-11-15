EXPEDO WordPress Theme 1.0.0
Instalare: WordPress > Aspect > Teme > Adaugă temă > Încarcă tema.
Instalează WooCommerce pentru catalog. Creează un meniu și atribuie-l locației Meniu principal.
În editarea fiecărui produs apare caseta EXPEDO – Link afiliat pentru URL-ul partenerului.
Fotografiile demo sunt externe (Unsplash) și trebuie înlocuite cu imagini pentru care există drepturi comerciale.
Newsletterul este doar vizual și trebuie conectat la Brevo/MailerLite.
Filtre avansate, comparație, favorite și import XML/API necesită extensii sau dezvoltare suplimentară.

Versiunea 1.1:
- Meniu superior cu legături de bază dacă nu este atribuit un meniu WordPress.
- Footer în trei coloane: Descoperă, Despre EXPEDO, Informații juridice.
- Creează paginile juridice și informative cu slug-urile din linkuri pentru a activa destinațiile.
- Pentru personalizare: Aspect > Meniuri și atribuie meniul la „Meniu principal”.

Versiunea 1.2: glosar preluat din tema Mobila Garde, inclusiv arhivă A–Z, pagini individuale, termeni asemănători, FAQ, schema DefinedTerm și dicționarul de 106 termeni. Import automat o singură dată. După activare, salvează din nou Setări > Legături permanente.

Versiunea 1.3:
- Mega-menu cu imagini generate din imaginile atribuite categoriilor/subcategoriilor WooCommerce.
- Arhivă de categorie cu tile-uri vizuale pentru subcategorii și imaginea fiecărei categorii.
- Filtre funcționale pentru interval de preț și atribute globale WooCommerce (de ex. culoare, material, dimensiuni dacă sunt create ca atribute globale).
- Layout responsive: filtre și catalog în două coloane pe desktop; meniu expandabil și filtre compacte pe mobil.
Configurare necesară:
1. Creează ierarhia de categorii WooCommerce și atribuie imagini categoriilor.
2. Creează atribute globale în Produse > Atribute (ex. Culoare, Material, Lățime, Înălțime, Adâncime) și configurează termenii.
3. Pentru fiecare produs, atribuie valorile atributelor globale, nu atribute text libere.
4. Mega-menu folosește categoriile principale WooCommerce; paginile de categorie afișează subcategoriile cu imagine.
Notă: filtrele pentru dimensiuni se bazează pe atribute globale dacă sunt configurate ca atare. Nu există încă filtre speciale de interval numeric pentru lățime/înălțime/adâncime.

Versiunea 1.4:
- Adaugă câmpuri numerice Lățime/Înălțime/Adâncime (cm) în editarea fiecărui produs.
- Filtrele de dimensiuni acceptă interval minim/maxim și interoghează valorile numerice salvate.
- Filtrele globale de culoare și material sunt prioritizate când atributele au slug-urile pa_color/pa_culoare și pa_material.
- Datele dimensiunilor trebuie completate la produse; produsele fără valori nu pot fi găsite prin filtrarea după dimensiuni.

Versiunea 1.5:
- Înlocuiește lista de categorii din header cu un singur buton „Categorii”.
- Desktop: hover pe „Categorii” deschide lista verticală a categoriilor principale; hover pe o categorie afișează în panoul din dreapta subcategoriile cu imagini.
- Mobil: atingere pe „Categorii” deschide lista; atingere pe o categorie extinde subcategoriile.
- Meniul folosește ierarhia product_cat WooCommerce și imaginile atribuite termenilor.

Versiunea 1.6: căutare fullscreen în modal, fundal blur/translucid, căutare limitată la produse WooCommerce (post_type=product), închidere prin X/fundal/Escape și focus automat.

Versiunea 1.7: wordmark EXPEDO actualizat conform referinței: „EX” light/thin și „PEDO” bold, aplicat în header și footer.

Versiunea 1.8: mega-menu-ul de categorii rămâne deschis la trecerea cursorului din buton în panoul lat. Închiderea are o întârziere de 450 ms și se anulează când cursorul intră în panou; Escape închide meniul.

EXPEDO v1.11 – funcționalități incluse:
- Catalog WooCommerce cu căutare, sortare, preț, intervale numerice pentru lățime/înălțime/adâncime și filtre pentru toate atributele globale WooCommerce.
- Filtrele dimensionale citesc metadatele _expedo_width_cm, _expedo_height_cm, _expedo_depth_cm; acestea trebuie completate pentru fiecare produs.
- Comparare până la 4 produse, salvate în localStorage în browser; butoane în catalog și produs, tabel comparativ în pagina /compara-produse/.
- Director magazine partenere prin tipul de conținut Magazin partener; fiecare intrare are titlu, descriere, imagine reprezentativă și câmp URL magazin.
- Pagini dedicate după slug pentru /magazine-partenere/, /compara-produse/, /devino-partener/, /contact/ și /politica-de-cookies/.
- Formularul de contact/parteneriat trimite la adresa administratorului WordPress (Setări > Generale); funcționarea e-mailului depinde de configurarea SMTP/serverului.
- Glosar A–Z și pagini individuale.
- Blog folosește articolele WordPress și categoriile existente (home.php).
- Pagina produsului afișează descrierea sub galerie. Textul SEO trebuie introdus/editorializat în descrierea produsului; tema nu inventează automat specificații și nu generează conținut AI.
- Banner simplu de preferințe cookies (alegere necesare/toate). Pentru conformitate reală trebuie auditate scripturile/cookie-urile și configurat CMP adecvat; bannerul simplu nu este o certificare juridică.
- Logo, culori, tipografie, header și layout Barlovento păstrate; căutarea din popup folosește pictograma lupă.
Configurare necesară:
1. Creează paginile cu slug-urile exacte: magazine-partenere, compara-produse, devino-partener, contact, politica-de-cookies. Atribuie pagina Blog ca „Pagina articolelor” în Setări > Citire.
2. Adaugă intrări la „Magazine partenere” din panoul WordPress și completează linkul magazinului.
3. Creează atribute globale WooCommerce (Culoare, Material, Brand etc.) și atribuie-le produselor.
4. Completează dimensiunile numerice în cm în fiecare produs.
5. Completează descrierea produsului cu text original și verificat.
6. Salvează din nou Setări > Legături permanente.
7. Configurează SMTP și verifică adresa administratorului pentru livrarea formularelor.


VERSIUNEA 1.18.0
- Banner homepage: imagini separate desktop/mobil din Appearance > Customize > EXPEDO – Banner principal.
- Breadcrumb-uri cu Acasă pentru categorii și produse.
- Arhiva Inspirații: filtre pe categorii și sortare după dată/titlu.
- Imagini native WooCommerce pentru antetul paginilor de categorie.
- Comparație: sincronizare robustă, validare date și eliminare individuală păstrată.
- Pagina produs: un singur titlu pentru produse similare și fără buton de coș în produsele similare.
- Buton afiliat: Vezi produsul.
- Corecție imagine dublată în pagina termenului de glosar.


EXPEDO v1.18
- Banner homepage full-width cu imagini desktop/mobile din Appearance > Customize > EXPEDO – Banner principal.
- Pagina Inspirații este configurată ca pagină de articole dacă nu există deja o pagină de articole selectată.
- Sunt create categoriile editoriale convenite dacă lipsesc. Atribuie articolele în aceste categorii.
- Pagina Compară produse este menținută publicată pentru funcționarea comparației.
- Butonul Înapoi sus apare după derulare.
- Numărul rezultatelor catalogului este mutat sub grila de produse.
- Imaginile glosarului se adaugă ca Imagine reprezentativă la fiecare termen.


VERSIUNEA 1.19.0
- Elimină etichetele decorative „EXPEDO · COLECȚIE” din arhiva catalogului și paginile categoriilor.
- Elimină eticheta decorativă „EXPEDO · SELECȚIE” din paginile produselor.
- Păstrează titlurile H1, breadcrumb-urile, descrierile și imaginile categoriilor.
- Reduce spațiul vertical și dimensiunea titlurilor în arhivele de produse și în pagina produsului.
- Păstrează numărul rezultatelor sub lista produselor în arhiva catalogului și paginile categoriilor.
