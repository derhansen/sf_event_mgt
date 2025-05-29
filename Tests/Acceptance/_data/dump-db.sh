#!/bin/sh

# Cleans up data from previous runs and dumps database for acceptance tests
mysql -u$DB_USERNAME -p$DB_PASSWORD typo3_sfeventmgt_acceptance_v13 -e "DELETE FROM tx_sfeventmgt_domain_model_registration WHERE uid NOT IN(1, 2, 203, 220); DELETE FROM tx_sfeventmgt_domain_model_registration_fieldvalue;" -h127.0.0.1 --port 33066
mysqldump --no-data -u$DB_USERNAME -p$DB_PASSWORD -h127.0.0.1 --port 33066 typo3_sfeventmgt_acceptance_v13 > typo3.sql
mysqldump --no-create-info \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.cache_adminpanel_requestcache \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.cache_adminpanel_requestcache_tags \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.cache_hash \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.cache_hash_tags \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.cache_pages \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.cache_pages_tags \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.cache_pagesection \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.cache_pagesection_tags \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.cache_rootline \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.cache_rootline_tags \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.cache_treelist \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.sys_history \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.sys_http_report \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.sys_lockedrecords \
          --ignore-table=typo3_sfeventmgt_acceptance_v13.sys_log \
          -u$DB_USERNAME -p$DB_PASSWORD -h127.0.0.1 --port 33066 typo3_sfeventmgt_acceptance_v13 >> typo3.sql
