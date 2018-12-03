<?php
$trans = array();

//	===================================================== //
//		Meniu 
//	===================================================== //
$trans['menu.dashboard'] = 'Dashboard';
$trans['menu.roles'] = 'Roluri';
$trans['menu.users'] = 'Utilizatori';
$trans['menu.users_permissions'] = 'Permisiuni Utilizatori';
$trans['menu.permissions'] = 'Declarare Permisiuni';
$trans['menu.spoken_languages'] = 'Limbi Vorbite';
$trans['menu.countries'] = 'Tari';
$trans['menu.messages_folders'] = 'Foldere Mesaje';
$trans['menu.messages'] = 'Mesaje';
$trans['menu.app_categories'] = 'Categoriile Aplicatiei';
$trans['menu.app_images'] = 'Imaginile Aplicatiei';
$trans['menu.categories'] = 'Categorii';
$trans['menu.categories_import'] = 'Categorii Import';
$trans['menu.products'] = 'Produse';
$trans['menu.products_images'] = 'Imaginile Produselor';


//	===================================================== //
//		general purpose messages
//	===================================================== //
$trans['general.private'] = 'Doar Webmaster';
$trans['general.save'] = 'Salveaza';
$trans['general.actions'] = 'Actiuni';
$trans['general.close'] = 'Inchide';
$trans['general.option_select'] = 'Va rugam sa selectati';
$trans['general.delete'] = 'Eliminati';
$trans['general.yes'] = 'Da';
$trans['general.no'] = 'Nu';
$trans['general.add'] = 'Adauga';
$trans['general.all_days'] = 'Toate zilele';
$trans['general.view'] = 'Vizualizare';
$trans['general.select_all'] = 'Selectati toate';
$trans['general.deselect_all'] = 'Deselectati toate';
$trans['general.define_rooms'] = 'Creati cel putin o camera!';
$trans['general.invalid_post'] = 'Datele introduse nu sunt corecte';
$trans['general.all_rights_reserved'] = 'Toate drepturile rezervate - tkid.ro';
$trans['general.sort_by'] = 'Sorteaza dupa ';
$trans['general.name'] = 'Nume';
$trans['general.description'] = 'Descriere';

$trans['general.image_deleted'] = 'Imaginea a fost stearsa';

$trans['general.attachments'] = 'Ataseaza fisiere';
$trans['general.url_key'] = 'Url Key';
$trans['general.regenerate_url_key'] = 'Regenereaza Url Key';

$trans['general.save_error'] = 'Eroare la salvare';
$trans['general.seo_keywords'] = 'Keywords SEO';
$trans['general.seo_description'] = 'Descriere SEO';
$trans['general.seo_title'] = 'Titlu SEO';
$trans['general.delete_image'] = 'Sterge imaginea';

//	===================================================== //
//		upload messages
//	===================================================== //
$trans['upload.error_file_max_size'] = 'Dimensiunea maxima permisa este %s';
$trans['upload.error_file_type_not_allowed'] = 'Tipul de fisier ales nu este permis. Puteti alege doar urmatoareale tipuri de fisier: JPG, PNG, GIF';
$trans['upload.error_no_file_selected'] = 'Nu a fost selectat nici un fisiser';
//	===================================================== //
//		reservations status
//	===================================================== //
$trans['reservations.status_canceled'] = 'Anulat';
$trans['reservations.status_accepted'] = 'Ok';


//	===================================================== //
//		days, months
//	===================================================== //
$trans['days.monday'] = 'Luni';
$trans['days.tuesday'] = 'Marti';
$trans['days.wednesday'] = 'Miercuri';
$trans['days.thursday'] = 'Joi';
$trans['days.friday'] = 'Vineri';
$trans['days.saturday'] = 'Sambata';
$trans['days.sunday'] = 'Duminica';

//	===================================================== //
//		metric units
//	===================================================== //
$trans['metric.meters'] = 'Metrii';
$trans['metric.kilometers'] = 'Kilometrii';
$trans['metric.feet'] = 'Picioare';
$trans['metric.miles'] = 'Mile';

//	===================================================== //
//		currency
//	===================================================== //
$trans['currency.RON'] = 'Lei';
$trans['currency.EURO'] = 'Euro';
$trans['currency.USD'] = 'Dolari';


//	===================================================== //
//		Blocul header : blocks/header.php
//	===================================================== //
$trans['header.title'] = 'Morashop - Panou de administrare';
$trans['header.user_profile'] = 'User Profile';
$trans['header.settings'] = 'Settings';
$trans['header.logout'] = 'Logout';

//	===================================================== //
//		Pagina de login : modulul login
//	===================================================== //
$trans['login.sign_in'] = 'Autentificare';
$trans['login.page_title'] = 'Login';
$trans['login.remember_me'] = 'Tine-ma minte';
$trans['login.login'] = 'Login';
$trans['login.password'] = 'Parola';
$trans['login.email'] = 'E-mail';
$trans['login.ip_restricted'] = 'Ip-ul este restrictionat !';

//	===================================================== //
//		Pagina de home : home.php		
//	===================================================== //
$trans['dashboard.page_title'] = 'Dashboard';

//	===================================================== //
//		Blocul search: blocks/search_block.php		
//	===================================================== //
$trans['search.search'] = 'Cauta...';

//	===================================================== //
//		Blocul search: blocks/search_master_users_block.php		
//	===================================================== //
$trans['search_master_users.search'] = 'Nume/Email';
$trans['search_master_users.hotel'] = 'Hotel';

//	===================================================== //
//		Pagina de vizualizare / editare utilizatori: users.php | user_edit.php	| settings.php
//	===================================================== //
$trans['users.page_title'] = 'Utilizatori';
$trans['users.users'] = 'Utilizatori';

$trans['users.options'] = 'Optiuni';

$trans['users.username'] = 'Nume Utilizator';
$trans['users.role'] = 'Rol';
$trans['users.hotel'] = 'Hotel';
$trans['users.email'] = 'Email';
$trans['users.first_name'] = 'Prenume';
$trans['users.last_name'] = 'Nume';

$trans['users.no_elements'] = 'Nu exista utilizatori.';
$trans['users.delete_selected_items'] = 'Sterge utilizatorii selectati';

$trans['users.edit_item'] = 'Editeaza utilizator';
$trans['users.delete_item'] = 'Sterge utilizator';

$trans['users.select_hotel'] = 'Alege Hotel';
$trans['users.current_password'] = 'Parola actuala';
$trans['users.password'] = 'Parola';
$trans['users.repeat_password'] = 'Repeta Parola';
$trans['users.limit_ip'] = 'Limitare IP';
$trans['users.active'] = 'Activ';
$trans['users.items_list'] = 'Lista Utilizatori';
$trans['users.new_item'] = 'Adauga Utilizator Nou';
$trans['users.edit_item'] = 'Editare Utilizator ';
$trans['users.edit_settings'] = 'Setari Utilizator ';
$trans['users.impersonate'] = 'Impersonare';

$trans['users.save_success'] = 'Utilizatorul a fost salvat';
$trans['users.delete_success'] = 'Utilizatorul a fost sters';
$trans['users.delete_selected_success'] = 'Utilizatorii selectati au fost sters';
$trans['users.error_selected_elements'] = 'Nu ati ales nici un user';
$trans['users.error_email_in_use'] = 'Acest email este deja folosit. Alegeti alt email';
$trans['users.save_settings_success'] = 'Modificarile au fost efectuate.';

//	===================================================== //
//		Pagina de vizualizare / editare roluri: roles.php | role_edit.php
//	===================================================== //
$trans['roles.page_title'] = 'Roluri';

$trans['roles.name'] = 'Nume';
$trans['roles.description'] = 'Descriere';
$trans['roles.status'] = 'Activ';

$trans['roles.no_elements'] = 'Nu exista roluri.';
$trans['roles.delete_selected_items'] = 'Sterge rolurile selectate';

$trans['roles.edit_item'] = 'Editeaza rol';
$trans['roles.delete_item'] = 'Sterge rol';

$trans['roles.items_list'] = 'Lista Roluri';
$trans['roles.new_item'] = 'Adauga Rol Nou';
$trans['roles.edit_item'] = 'Editare Rol ';
$trans['roles.save_success'] = 'Rolul a fost salvat';
$trans['roles.delete_success'] = 'Rolul a fost sters';
$trans['roles.delete_selected_success'] = 'Rolurile selectate au fost sterse';
$trans['roles.error_selected_elements'] = 'Nu ati ales nici un rol';

//	===================================================== //
//		Pagina de vizualizare / editare lista permisiuni: permissions.php	| permission_edit.php		
//	===================================================== //
$trans['permissions.page_title'] = 'Management Permisiuni';
$trans['permissions.new_item'] = 'Adauga permisiune';
$trans['permissions.edit_item'] = 'Editare permisiune';
$trans['permissions.item_not_exists'] = 'Permisiunea editata nu exista';
$trans['permissions.delete_selected_items'] = 'Sterge permisiunile selectate';
$trans['permissions.error_selected_elements'] = 'Nu ati ales nici o permisiune';

$trans['permissions.save_success'] = 'Permisiunea a fost salvata';
$trans['permissions.delete_success'] = 'Permisiunea a fost sters';
$trans['permissions.delete_selected_success'] = 'Permisiunile au fost sterse';

$trans['permissions.page_id'] = 'Id Pagina';

$trans['permissions.items_list'] = 'Lista Permisiuni';

//	===================================================== //
//		Paginile de vizualizare / editare languages: languages.php | language_edit.php
//	===================================================== //
$trans['languages.page_title'] = 'Management Limbi Vorbite';
$trans['languages.new_item'] = 'Adauga limba noua';
$trans['languages.edit_item'] = 'Editare limba';
$trans['languages.item_not_exists'] = 'Limba editata nu exista';
$trans['languages.delete_selected_items'] = 'Sterge limbile selectate';

$trans['languages.name'] = 'Nume';
$trans['languages.abbreviation'] = 'Abreviere';
$trans['languages.default_language'] = 'Limba Implicita';
$trans['languages.has_translation'] = 'Are Traducere';

$trans['languages.items_list'] = 'Lista Limbi Vorbite';

$trans['languages.item_exists_error'] = 'O limba cu aceeasi abreviere exista deja!';

$trans['languages.save_success'] = 'Limba a fost salvata cu success';
$trans['languages.delete_success'] = 'Limba a fost stearsa';
$trans['languages.delete_selected_success'] = 'Limbile selectate au fost sterse';
$trans['languages.error_selected_elements'] = 'Nu ati ales nici o limba';

//	===================================================== //
//		Pagina de vizualizare / editare foldere mesaje: messages_folders.php	| messages_folder_edit.php		
//	===================================================== //
$trans['messages_folders.page_title'] = 'Management Foldere Mesaje';
$trans['messages_folders.new_item'] = 'Adauga folder mesaje';
$trans['messages_folders.edit_item'] = 'Editare folder mesaje';
$trans['messages_folders.item_not_exists'] = 'Folderul editat nu exista';
$trans['messages_folders.delete_selected_items'] = 'Sterge folderele selectate';
$trans['messages_folders.error_selected_elements'] = 'Nu ati ales nici un folder';

$trans['messages_folders.css_class'] = 'Clasa CSS';
$trans['messages_folders.role'] = 'Rol';

$trans['messages_folders.label_name'] = 'Nume';
$trans['messages_folders.label_css_class'] = 'Clasa CSS';
$trans['messages_folders.label_role'] = 'Rol';

$trans['messages_folders.items_list'] = 'Lista foldere';

$trans['messages_folders.save_success'] = 'Folderul a fost salvat';
$trans['messages_folders.delete_success'] = 'Folderul a fost sters';
$trans['messages_folders.delete_selected_success'] = 'Folderele selectate au fost sterse';



//	===================================================== //
//		Pagina de vizualizare / trimiteree mesaje: messages.php
//	===================================================== //
$trans['messages.page_title'] = 'Management Foldere Mesaje';
$trans['messages.new_item'] = 'Trimite mesaj nou';
$trans['messages.edit_item'] = 'Editare mesaj';
$trans['messages.item_not_exists'] = 'Mesajul editat nu exista';
$trans['messages.delete_selected_items'] = 'Sterge mesajele selectate';

$trans['messages.subject'] = 'Subiect';
$trans['messages.from'] = 'Expeditor';
$trans['messages.to'] = 'Destinatar';
$trans['messages.date_sent'] = 'Data trimiterii';

$trans['messages.label_subject'] = 'Subiect';
$trans['messages.label_message'] = 'Mesaj';

$trans['messages.items_list'] = 'Lista mesaje';
$trans['messages.send_message'] = 'Trimite mesaj';
$trans['messages.new_message_title'] = 'Mesaj nou';
$trans['messages.view_message_title'] = 'Vizualizare mesaj';


$trans['messages.option_select'] = '-- Toate subiectele --';
 
$trans['messages.status_read'] = 'Acest mesaj a fost citit. Dati click pentru a-l marca ca Necitit';
$trans['messages.status_not_read'] = 'Acest mesaj nu a fost citit.';

$trans['messages.status_not_starred'] = 'Dati clic pe stea pentru a selecta mesajul si a-l copia intr-un folder special numit Marcate pentru a putea fi consultate mai tarziu.';
$trans['messages.status_starred'] = 'Dati clic pe stea pentru a scoate mesajul din folderul Marcate.';

$trans['messages.delete'] = 'Arhivati acest mesaj. Mesajul dvs. va fi mutat in folderul Arhivate';
$trans['messages.undo_delete'] = 'Mesajul dvs. va fi mutat in folderul Toate mesajele';

$trans['messages.read_status_set_read'] = 'Mesajul a fost marcat ca citit.';
$trans['messages.read_status_set_not_read'] = 'Mesajul a fost marcat ca necitit.';

$trans['messages.reply'] = 'Raspunde';

$trans['messages.message_deleted'] = 'Mesajul a fost arhivat';
$trans['messages.message_starred'] = 'Mesajul a fost marcat';
$trans['messages.message_unstarred'] = 'Mesajul a fost scos de la marcate';
$trans['messages.message_sent'] = 'Mesajul a fost trimis';

$trans['messages.delete_success'] = 'Mesajul a fost sters';
$trans['messages.delete_selected_success'] = 'Mesajele selectate au fost sterse';
$trans['messages.error_selected_elements'] = 'Nu ati ales nici un mesaj';

//	===================================================== //
//		Pagina de vizualizare / editare tari: countries.php | country_edit.php
//	===================================================== //
$trans['countries.page_title'] = 'Tari';

$trans['countries.name'] = 'Nume';
$trans['countries.abbreviation'] = 'Abreviere';
$trans['countries.status'] = 'Activ';

$trans['countries.no_elements'] = 'Nu exista tara.';
$trans['countries.delete_selected_items'] = 'Sterge tarile selectate';

$trans['countries.edit_item'] = 'Editeaza tara';
$trans['countries.delete_item'] = 'Sterge tara';

$trans['countries.items_list'] = 'Lista Tari';
$trans['countries.new_item'] = 'Adauga tara noua';
$trans['countries.edit_item'] = 'Editare Tara ';
$trans['countries.save_success'] = 'Tara a fost salvata';
$trans['countries.delete_success'] = 'Tara a fost stearsa';
$trans['countries.delete_selected_success'] = 'Tarile selectate au fost sterse';
$trans['countries.error_selected_elements'] = 'Nu ati ales nici o tara';

//	===================================================== //
//		Pagina de editare permisiuni utilizatori: users_permission.php		
//	===================================================== //
$trans['users_permissions.page_title'] = 'Management permisiuni utilizatori';
$trans['users_permissions.select_user'] = 'Alege utilizator';
$trans['users_permissions.select_user_option'] = '-- Selecteaza utilizator --';
$trans['users_permissions.select_permissions'] = 'Selecteaza Permisii';
$trans['users_permissions.check_all'] = 'Selecteaza toate permisiunile';
$trans['users_permissions.save'] = 'Salveaza Permisiuni';
$trans['users_permissions.save_success'] = 'Permisiunile au fost salvate';

//	===================================================== //
//		Pagina de editare permisiuni utilizatori: users_permission.php		
//	===================================================== //
$trans['generate_code.page_title'] = 'Generare cod';
$trans['generate_code.select_table'] = 'Alege tabel';


//	===================================================== //
//		Pagina de vizualizare / editare produse: products.php | product_edit.php
//	===================================================== //
$trans['products.page_title'] = 'Produse:';



$trans['products.producer'] = 'Producator';
$trans['products.model'] = 'Model';
$trans['products.product_code'] = 'Cod';
$trans['products.name'] = 'Nume';
$trans['products.category'] = 'Categorie';
$trans['products.categories'] = 'Categorii';
$trans['products.sizes'] = 'Marimi';
$trans['products.description'] = 'Descriere';
$trans['products.link'] = 'Link';
$trans['products.default_image'] = 'Imagine';
$trans['products.price'] = 'Pret';
$trans['products.price_before'] = 'Pret anterior';
$trans['products.images'] = 'Imagini';
$trans['products.amount'] = 'Cantitate';
$trans['products.amount_unit'] = 'Unitate de masura';

$trans['products.status'] = 'Produs status';
$trans['products.status_checkbox'] = 'Produs activ';

$trans['products.image'] = 'Imagine';

$trans['products.no_elements'] = 'Nu exista produsul.';
$trans['products.delete_selected_items'] = 'Sterge produsele selectate';

$trans['products.edit_item'] = 'Editeaza produs';
$trans['products.delete_item'] = 'Sterge produs';

$trans['products.items_list'] = 'Lista Produse';
$trans['products.new_item'] = 'Adauga produs nou';
$trans['products.new_item_in_category'] = 'Adauga produs nou sub aceleasi categorii';
$trans['products.edit_item'] = 'Editare produs ';
$trans['products.save_success'] = 'Produsul a fost salvat';
$trans['products.delete_success'] = 'Produsul a fost sters';
$trans['products.delete_selected_success'] = 'Produsele selectate au fost sterse';
$trans['products.error_selected_elements'] = 'Nu ati ales nici un produs';

$trans['products.status.Active'] = 'Activ';
$trans['products.status.Inactive'] = 'Inactiv';

//	===================================================== //
//		Pagina de vizualizare / editare categorii produse: categories.php | product_category_edit.php
//	===================================================== //
$trans['categories.page_title'] = 'Categorii';
$trans['categories.no_elements'] = 'Nu exista categorii.';
$trans['categories.delete_selected_items'] = 'Sterge categoriile selectate';

$trans['categories.name'] = 'Categorie';
$trans['categories.edit_articles_items'] = 'Lista produse';


$trans['categories.edit_item'] = 'Editeaza categorie';
$trans['categories.delete_item'] = 'Sterge categorie';

$trans['categories.items_count'] = '%s produse';
$trans['categories.item_count'] = '%s produs';
$trans['categories.subcategories_count'] = '%s subcategorii';
$trans['categories.subcategory_count'] = '%s subcategorie';

$trans['categories.items_list'] = 'Lista categorii';
$trans['categories.new_item'] = 'Adauga categorie noua';
$trans['categories.edit_item'] = 'Editare categorie ';
$trans['categories.save_success'] = 'Categoria a fost salvat';
$trans['categories.delete_success'] = 'Categoria a fost stearsa';
$trans['categories.delete_selected_success'] = 'Categoriile selectate au fost sterse';
$trans['categories.error_selected_elements'] = 'Nu ati ales nici o categorie';

$trans['categories.main_category'] = 'Main';
$trans['categories.parent'] = 'Parinte';
$trans['categories.description'] = 'Descriere';
$trans['categories.short_description'] = 'Descriere Scurta';
$trans['categories.is_active'] = 'Activa / Inactiva (daca este debifat, nu va fi afisata deloc)';
$trans['categories.is_separate'] = 'Daca selectati aceasta optiune categoria nu va fi afisata in meniu alaturi de celelalte categorii, ci  doar pe prima pagina !';
$trans['categories.image'] = 'Imagine';
$trans['categories.order'] = 'Ordinea afisarii categoriilor';

$trans['categories.item_not_exists'] = 'Categoria editata nu exista';
$trans['categories.save_success'] = 'Categoria a fost salvata';

$trans['categories.status.Active'] = 'Activ';
$trans['categories.status.Inactive'] = 'Inactiv';
$trans['categories.diplayStatus.Separated'] = 'Afisare separata';
$trans['categories.displayStatus.United'] = 'Afisare in meniu';

//	===================================================== //
//		Pagina de vizualizare / editare categorii produse: categories.php | product_category_edit.php
//	===================================================== //
$trans['pictures.page_title'] = 'Poze Produs';
$trans['picture_crop.page_title'] = 'Crop Poza';
$trans['pictures.choose_default_image'] = 'Seteaza ca poza principala';
$trans['pictures.set_default_success'] = 'Setarea imaginii reusita !';

$trans['app_image_edit.page_title'] = 'Editare: ';
$trans['app_image.alt'] = 'Text afisat in lispa pozei: ';
$trans['app_image.title'] = 'Titlul pozei la mutarea cursorului peste ea: ';
$trans['app_image.caption'] = 'Indexul textului din fisierul de traducere. Va fi afisat peste poza: ';
$trans['app_image.description'] = 'Textul in limba romana afisat peste poza: ';
$trans['app_image.button_link_text'] = 'Textul din butonul afisat peste poza: ';
$trans['app_image.button_link_hfer'] = 'Adresa textului din butonul afisat peste poza: ';
$trans['app_image.order_index'] = 'Ordinea afisarii pozei: ';

$trans['app_images.page_title'] = 'Poze';
$trans['app_images.item_not_exists'] = 'Poza nu exista';
$trans['app_images.save_success'] = 'Poza salvata cu succes';
$trans['app_images.no_images'] = 'Nu exista poze pentru aceasta categorie';
$trans['app_images.app_categories'] = 'Alegeti categoria';
$trans['pictures.order_save_success'] = 'Ordinea pozelor a fost modificata';

//	===================================================== //
//		Pagina de vizualizare / contact.php
//	===================================================== //

$trans['contact.page_title'] = 'Contact Us';
?>
