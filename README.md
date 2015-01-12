
# ![Smoothieme](/public/img/smoothiemelogo_small.png)

Smoothieme ist ein Studien-Projekt, das 2014/15 im Rahmen der Vorlesung Webtechnologie entstanden ist.
Ziel war es einen Shop-System zu entwickeln, das auf den Vertrieb von Smoothies optimiert ist.


## Dokumentation + Präsentation
In [/docs] finden Sie Spezifikation und Präsentions-Daten

## Installation
### Per Git
1. Prüfen Sie, dass die Systemvoraussetzungen von Smoothieme gegeben sind: 
    * PHP Version > PHP 5.2.10
    * PHP-PDO installiert inkl. Mysql-Adapter
    * Mysql-Server Version > 5.0
    * Imagick / Images-Magic Erweiterung muss installiert sein um Bilder bearbeiten zu können

2. Clonen Sie das Repository
     ```sh
       % git clone git@github.com:sadeq89/smoothieme.git
       % cd smoothieme
       ```
    * Fügen Sie das Verzeichnis `library` zu `include_path` hinzu
    * Stellen Sie sicher, dass `public` das Document-Root ist

3. Überprüfen und beenden Sie die Installation
    * ... indem Sie `http://what-ever-your-domain-is.tld/install.php` im Browser aufrufen!

4. Entfernen Sie `public/install.php`
   




## Weiterführende Informationen für Entwicklung

### Linksammlung

[Vid-tut-1](http://cloud.webtaurus.de/public.php?service=files&t=ccaaf656da37b05e06f46dba3afbbf55)
___ACHTUNG:___ Dieses Fenster bzgl. Line-endings wie gesehen bestätigen:
![Alt text](/docs/git_phpstorm_autocrlf.png)

[Zend learn guide](http://framework.zend.com/manual/1.12/de/learning.html)

[Zend tool Übersicht](http://richardjh.org/files/richardjh_zend-tool.pdf) (Erstellen von Controllern, Views, Config-Modes setzen, etc....)

[Vagraooo setup](https://github.com/sadeq89/smoothieme/blob/master/DEVELOPMENT_README.md)

[ZF Installation](https://github.com/sadeq89/smoothieme/blob/master/INSTALL.md)
### Bei Vagrant-Problemen...
Trat gerade bei mir auf, war zu lösen (wenn auch unlogisch, hätte nicht zum Erfolg führen dürfen)
[https://github.com/puphpet/puphpet/issues/456]
