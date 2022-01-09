wp option update blogname "art-sei.com" --allow-root
wp option update blogdescription "生活に密着した生の人間を描きたい。現代画家、千光士誠のサイト"  --allow-root
wp option update admin_email "tkunisada@gmail.com"  --allow-root

wp theme activate art-sei-com --allow-root

wp post create --post_title='トップページ' --post_name='front-page' --post_type=page --post_status=publish  --allow-root
wp post create --post_title='作品一覧' --post_name='portfolio' --post_type=page --post_status=publish  --allow-root
wp post create --post_title='経歴' --post_name='profile' --post_type=page --post_status=publish  --allow-root
wp post create --post_title='エッセイ' --post_name='essays' --post_type=page --post_status=publish  --allow-root