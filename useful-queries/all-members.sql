-- Create a CSV file of member name, email and Location
SELECT wpu.display_name, wpu.user_email, bpx.value
FROM wp_bp_xprofile_data AS bpx,
     wp_users AS wpu,
     wp_usermeta AS wpm,
     wp_bp_xprofile_fields AS bpf
WHERE bpf.name='Location'
  AND wpm.user_id=wpu.ID
  AND wpm.meta_key='wp_capabilities'
  AND wpm.meta_value LIKE '%subscriber%'
  AND bpx.user_id=wpu.ID
  AND bpx.field_id=bpf.id
INTO OUTFILE '/tmp/members.csv'
     FIELDS TERMINATED BY ','
     ENCLOSED BY '"'
     LINES TERMINATED BY '\n';
