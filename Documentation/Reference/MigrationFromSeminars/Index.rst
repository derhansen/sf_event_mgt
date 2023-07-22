.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt

.. _migration-from-seminars:

===========================
Migration from EXT:seminars
===========================

We migrated an instance from EXT:seminars v2.1 to EXT:sf_event_mgt v4.3

What's included?
^^^^^^^^^^^^^^^^

* Events
* Registrations
* Categories
* Locations (multiple => single)
* Organizers (multiple => single)
* Speaker

We also migrated event types and target groups. The sql queries are basically the same like the category queries. You will need two more category fields in tx_sfeventmgt_domain_model_event for them.

SQL queries
^^^^^^^^^^^

We are starting with a fresh install of sf_event_mgt so we can use ids mostly 1 to 1.

.. code-block:: mysql

    /* Organizers */
    INSERT INTO tx_sfeventmgt_domain_model_organisator (uid,pid,name,email,email_signature)
    SELECT
        uid,
        pid,
        title,
        email,
        email_footer,
    FROM
        tx_seminars_organizers
    WHERE
        deleted = 0;

    /* Locations */
    INSERT INTO tx_sfeventmgt_domain_model_location (uid,pid,title,address,city,description)
    SELECT
        uid,
        pid,
        title,
        address,
        city,
        directions
    FROM
        tx_seminars_sites
    WHERE
        deleted = 0;

    /* Speakers */
    INSERT INTO tx_sfeventmgt_domain_model_speaker (uid,pid,name,description)
    SELECT
        uid,
        pid,
        title,
        description
    FROM
        tx_seminars_speakers
    WHERE
        deleted = 0;

    /* Description of speakers */
    UPDATE tx_sfeventmgt_domain_model_speaker
    SET
        description = (
            SELECT
                CONCAT(
                    IF (organization!='', CONCAT(organization, '<br>'), ''),
                    IF (homepage!='', CONCAT('<a href="', homepage,'">', homepage, '</a><br>'), ''),
                    IF (email !='', CONCAT('<a href="mailto:', email, '">', email, '</a><br>'), ''),
                    description
                )
            FROM
                tx_seminars_speakers
            WHERE
                tx_sfeventmgt_domain_model_speaker.uid = tx_seminars_speakers.uid
        );

    /* Events */
    INSERT INTO tx_sfeventmgt_domain_model_event (
        uid,
        pid,
        hidden,
        title,
        teaser,
        description,
        startdate,
        enddate,
        registration_deadline,
        cancel_deadline,
        enable_cancel,
        location,
        room,
        speaker,
        price,
        enable_registration,
        unique_email_check,
        max_participants,
        registration,
        enable_autoconfirm,
        enable_waitlist,
        notify_organisator
    )
    SELECT
        uid,
        pid,
        hidden,
        title,
        teaser,
        CONCAT(
            IF (
                subtitle!='',
                CONCAT(
                    '<h2>',
                    subtitle,
                    '</h2>'
                ),
                ''
            ),
            description,
            additional_information
        ),
        begin_date,
        end_date,
        deadline_registration,
        deadline_unregistration,
        1, /* Enable cancellation */
        place,
        room,
        speakers,
        price_regular,
        needs_registration,
        IF(
            allows_multiple_registrations = 1,
            '0',
            '1'
        ),
        attendees_max,
        registrations,
        1, /* auto confirm registration */
        queue_size,
        1 /* notify organisator on new registration */
    FROM tx_seminars_seminars
    WHERE
        deleted = 0 AND
        (end_date = 0 OR end_date >= UNIX_TIMESTAMP()); /* filter out past events */

    /* Events <=> Locations */
    UPDATE tx_sfeventmgt_domain_model_event
        SET location = (
            SELECT
                uid_foreign
            FROM
                tx_seminars_seminars_place_mm
            WHERE
                tx_sfeventmgt_domain_model_event.uid = tx_seminars_seminars_place_mm.uid_local
            LIMIT 1 /* seminars has multiple locations, this extension has only a single location */
        );

    /* Events <=> Speaker */
    INSERT INTO tx_sfeventmgt_event_speaker_mm (uid_local,uid_foreign,sorting)
    SELECT
        uid_local,
        uid_foreign,
        sorting
    FROM tx_seminars_seminars_speakers_mm
    WHERE
        uid_local IN (SELECT uid FROM tx_sfeventmgt_domain_model_event);

    /* Events <=> Organizers */
    UPDATE tx_sfeventmgt_domain_model_event
        SET organisator = (
            SELECT
                uid
            FROM tx_seminars_organizers
            LEFT JOIN tx_seminars_seminars_organizers_mm
                ON
                    tx_seminars_seminars_organizers_mm.uid_foreign = tx_seminars_organizers.uid
            WHERE
                tx_seminars_seminars_organizers_mm.uid_local = tx_sfeventmgt_domain_model_event.uid AND
                tx_seminars_organizers.deleted = 0
            ORDER BY
                sorting ASC
            LIMIT 1 /* seminars has multiple organizers, this extension only a single organizer */
        );

    /* Categories */
    INSERT INTO sys_category (uid,pid,title,parent)
    SELECT
        uid + 10000, /* bigger than your highest category uid */
        pid,
        title,
        823  /* Parent category for our event categories */
    FROM
         tx_seminars_categories
    WHERE
         deleted = 0;

    /* Events <=> Categories */
    INSERT INTO sys_category_record_mm (uid_local,uid_foreign,tablenames,fieldname)
    SELECT
        uid_foreign + 10000,
        uid_local,
        'tx_sfeventmgt_domain_model_event',
        'category'
    FROM tx_seminars_seminars_categories_mm
    WHERE
        tx_seminars_seminars_categories_mm.uid_local IN (SELECT uid FROM tx_sfeventmgt_domain_model_event);

    /* Registrations */
    INSERT INTO tx_sfeventmgt_domain_model_registration (
        uid,
        pid,
        hidden,
        tstamp,
        crdate,
        fe_user,
        `event`,
        waitlist,
        notes,
        firstname,
        lastname,
        email,
        confirmed,
        amount_of_registrations
    )
    SELECT
        uid,
        pid, /* Your need to make sure registrations the same pid as event records (Inline relation) */
        hidden,
        tstamp,
        crdate,
        `user`,
        seminar,
        registration_queue,
        CONCAT(
            IF (attendees_names!='', CONCAT('Weitere Namen:\n',attendees_names,'\n\n'), ''),
            IF (interests!='', CONCAT('Interessen:\n',interests,'\n\n'), ''),
            IF (expectations!='', CONCAT('Erwartungen:\n',expectations,'\n\n'), ''),
            IF (background_knowledge!='', CONCAT('Vorkenntnisse:\n',background_knowledge,'\n\n'), ''),
            IF (known_from!='', CONCAT('Wie haben Sie von dieser Veranstaltung erfahren?\n',known_from,'\n\n'), ''),
            IF (notes!='', CONCAT('Sonstiges:\n',notes,'\n\n'), '')
        ),
        fe_users.first_name,
        fe_users.last_name,
        fe_users.email,
        1,
        seats
    FROM tx_seminars_attendances
    LEFT JOIN fe_users
        ON fe_users.uid=tx_seminars_attendances.user
    LEFT JOIN tx_seminars_seminars ON
        tx_seminars_seminars.uid = tx_seminars_attendances.seminar
    WHERE
        tx_seminars_attendances.deleted = 0 AND
        tx_seminars_seminars.deleted = 0 AND
        tx_seminars_attendances.seminar IN (
            SELECT
                uid
            FROM
                tx_sfeventmgt_domain_model_event
        );
