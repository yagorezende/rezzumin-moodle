## Rezzumin Moodle Plugin
Rezzumin is an open-source moodle plugin implemented using PHP and Python.
Note that, to work properly, the plugin has the permission to write in the
[***$CFG->dataroot***](https://docs.moodle.org/400/en/Configuration_file)
folder, some ___cache___ file may be created. Also, the text
summarization is mainly made with python script, some dependencies
will be installed.

The project is part of a project powered by [CNPq](https://www.gov.br/cnpq/),
under the supervision of [Prof. Dr. Diogo Mattos](https://www.labgen.lid.uff.br/site/index.php/equipe/prof-diogo/).

<img src="pix/icon.png" alt="rezzumin plugin logo">

---
### Features
1. Input raw text
2. Input .txt or .pdf file
3. Manage and store multiple texts
4. Generate summarized text
---
### Setup
Note: Don't execute the setup_converter.sh script as root, use a sudo user!
add Listen 8181 on /etc/apache2/ports.conf

Powered by: [CNPq](https://www.gov.br/cnpq/)
<br>
Developed by: [@yagorezende](https://twitter.com/codepython)
