#获取需要建站的域名
domain=$1
#接收传入的产品品牌名
new_brand=$2
#接收传入的产当前品牌编号
num=$3
new_password=$4
#转大写以便替换数据库
typeset -u capitalize_prefix
capitalize_prefix=$new_brand
#获取文件中需要被替换的域名
file=`sed -n '2p' includes/configure.php | grep -Eo "https:\/\/.*\.com|https:\/\/.*\.shop|http:\/\/.*\.shop|http:\/\/.*\.com" | cut -d / -f 3`
#获取根域名
replace=${file/www./""}
#获取当前站点的编号
current_num=`ls ./ | grep manager$ | grep  -Eo "[0-9]+"`

if [ ${current_num:0:1} = '0' ];then
    current_num=${current_num:1:99999}
fi

typeset -u capitalize_old_brand
#获取当前品牌名
old_brand=`ls ./ | grep we-manager$ | grep  -Eo "^[a-zA-Z]+"  `
capitalize_old_brand=$old_brand;


mkdir -p cache
mkdir -p images/cache

rm  -rf cache/*
rm  -rf images/cache/*

chmod -R 777 cache
chmod -R 777 images/cache

if [ $current_num -lt 10 ];
    then  tmp_current_num='0'${current_num}
else
    tmp_current_num=${current_num}
fi
if [ $num -lt 10 ];
    then  tmp_num='0'${num}
else
    tmp_num=$num;
fi

old_password=`cat ${old_brand}${tmp_current_num}we-manager/includes/configure.php | grep -Eo "(define\('ADMIN_PASSWORD', ')(.*)('\);)" `
old_password=`echo $old_password | cut -d, -f 2 | grep  -Eo "[a-zA-Z0-9]+" `

manager="${old_brand}${tmp_current_num}we-manager"
manager_zp="${manager}_zp";

new_manager="${new_brand}${tmp_num}we-manager"
new_manager_zp="${new_brand}${tmp_num}we-manager_zp"

rename "s/$manager/$new_manager/" *
rename "s/$manager_zp/$new_manager_zp/" *

# 前台配置文件更改
sed -i "s/$replace/$domain/g" "includes/configure.php" #正常
sed -i "s/$capitalize_old_brand${tmp_current_num}WE/$capitalize_prefix${tmp_num}WE/g" "includes/configure.php"

# # 后台配置文件更改
sed -i "s/$replace/$domain/g" "${new_manager}/includes/configure.php"
sed -i "s/$old_password/$new_password/g" "${new_manager}/includes/configure.php"
sed -i "s/$capitalize_old_brand/$capitalize_prefix/g" "${new_manager}/includes/configure.php"
sed -i "s/$tmp_current_num/$tmp_num/g" "${new_manager}/includes/configure.php"
sed -i "s/$old_brand/$new_brand/g" "${new_manager}/includes/configure.php"

#后台规避配置文件更改
sed -i "s/$replace/$domain/g" "${new_manager_zp}/includes/configure.php"
sed -i "s/$old_password/$new_password/g" "${new_manager_zp}/includes/configure.php"
sed -i "s/$capitalize_old_brand/$capitalize_prefix/g" "${new_manager_zp}/includes/configure.php"
sed -i "s/$tmp_current_num/$tmp_num/g" "${new_manager_zp}/includes/configure.php"
sed -i "s/$old_brand/$new_brand/g" "${new_manager_zp}/includes/configure.php"