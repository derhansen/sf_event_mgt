#!/bin/sh

# Cleans up data from previous runs and dumps database for acceptance tests
mysql -u$DB_USERNAME -p$DB_PASSWORD typo3_sfeventmgt_acceptance_v12 -e "DELETE FROM tx_sfeventmgt_domain_model_registration WHERE uid NOT IN(1, 2, 203, 220); DELETE FROM tx_sfeventmgt_domain_model_registration_fieldvalue;"
mysqldump --no-data -u$DB_USERNAME -p$DB_PASSWORD typo3_sfeventmgt_acceptance_v12 > typo3.sql
mysqldump --no-create-info \
          --ignore-table=typo3_sfeventmgt_acceptance_v11.sys_log \
          --ignore-table=typo3_sfeventmgt_acceptance_v11.cache_hash \
          --ignore-table=typo3_sfeventmgt_acceptance_v11.cache_hash_tags \
          --ignore-table=typo3_sfeventmgt_acceptance_v11.cache_pages \
          --ignore-table=typo3_sfeventmgt_acceptance_v11.cache_pages_tags \
          --ignore-table=typo3_sfeventmgt_acceptance_v11.cache_pagesection \
          --ignore-table=typo3_sfeventmgt_acceptance_v11.cache_pagesection_tags \
          --ignore-table=typo3_sfeventmgt_acceptance_v11.cache_rootline \
          --ignore-table=typo3_sfeventmgt_acceptance_v11.cache_rootline_tags \
          -u$DB_USERNAME -p$DB_PASSWORD typo3_sfeventmgt_acceptance_v12 >> typo3.sql
