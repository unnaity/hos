-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2025 at 07:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hos`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CHECK_REFERAL_CODE` (IN `REFERAL_CODE` VARCHAR(255))   BEGIN
	IF EXISTS(
				SELECT 1 
					FROM tbl_customer C 
					WHERE C.referal_code=REFERAL_CODE AND C.status='1' AND C.is_deleted='0'
			 ) 
					THEN
		SELECT "" as message; 
	ELSE
		BEGIN 
			SELECT "Invalid Referal Code" as message;
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CHECK_VALID_COUPON` (IN `COUPON_CODE` VARCHAR(200), IN `USER_ID` BIGINT(20))   BEGIN
	IF EXISTS(
				SELECT 1 
				FROM tbl_customer 
				WHERE (ID=USER_ID AND status='1' AND is_deleted='0') OR 1=1
			 ) 
	THEN 
		BEGIN
			IF EXISTS(
						SELECT 1 
						FROM tbl_coupon_code 
						WHERE code=COUPON_CODE 
							  AND expiry_date >= CURDATE()
					 ) 
			THEN
				BEGIN
					SELECT * 
					FROM tbl_coupon_code TCC 
					WHERE TCC.status='1' 
						  AND TCC.isDeleted='0';
				END;
			ELSE
				BEGIN
					SELECT 'Invalid Coupon Code' as message;
				END; 
			END IF;
		END;
	ELSE
		BEGIN
			SELECT 'Invalid User' as message;
		END; 
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DUPLICATE_CHECK_CUSTOMER_MOBILE_NO` (IN `MOBILE_NO` BIGINT(20))   BEGIN
	IF EXISTS(SELECT 1 
				FROM tbl_customer C 
			  	INNER JOIN tbl_login L ON L.user_id=C.customer_id 
			  	WHERE (C.mobile_no=MOBILE_NO OR L.mobile_no=MOBILE_NO) AND L.user_type_id = 3
			  ) 
			  THEN
		SELECT "Mobile no. already registered" as message;
	ELSE
		BEGIN
			SELECT "" as message;
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DUPLICATE_CHECK_EMAIL` (IN `EMAIL` VARCHAR(255))   BEGIN
	IF EXISTS(SELECT 1 FROM tbl_customer C 
				INNER JOIN tbl_login L ON L.user_id=C.customer_id 
				WHERE (C.email=EMAIL OR L.email=EMAIL) AND L.user_type_id=3
			  ) 
	THEN
		SELECT "Email Id already registered" as message;
	ELSE
		BEGIN 
			SELECT "" as message;
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_ADMIN_MENU` (IN `USER_ID` BIGINT, IN `USER_TYPE` BIGINT, IN `PARENT_ID` BIGINT)   BEGIN
	SELECT 
	tam.admin_module_id 
	, tam.module_name
	, tam.access_url
	, (SELECT COUNT(1) FROM tbl_admin_module tam2 WHERE tam.admin_module_id=tam2.parent_id AND tam2.status=1 GROUP BY tam2.parent_id) child_count
	FROM tbl_admin_module tam 
	INNER JOIN tbl_user_permission UP ON UP.module_id=tam.admin_module_id
	WHERE (UP.create_permission=1 OR UP.update_permission=1 OR UP.delete_permission=1 OR UP.view_permission=1)
	AND tam.parent_id=PARENT_ID 
	AND tam.status='1' 
	AND UP.status='1'
	AND UP.user_id=USER_ID AND UP.user_type=USER_TYPE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_ADMIN_MODULE_NAME` ()   BEGIN
	SELECT am.admin_module_id, am.module_name, am.menu_title, am.status, am.order_by 
	FROM tbl_admin_module am
	WHERE am.status = '1' ORDER BY am.order_by ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_ADMIN_USER_LIST` (IN `USER_ID` BIGINT)   BEGIN
	SELECT
		U.id
		,U.name
		,U.email
		,U.mobile_no
		,U.address
		,U.dob
		,U.identity_no
		,U.user_type
		,U.register_date
		,U.status 
		,UT.user_type 
		,(SELECT COUNT(1) FROM tbl_user_permission UP WHERE UP.user_id=U.id AND UP.user_type=8) permission
	FROM tbl_user U
	INNER JOIN tbl_user_type UT ON UT.id=U.user_type
	WHERE U.is_deleted='0' AND U.user_type=8 
	AND (U.id != USER_ID OR USER_ID = 0);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_ADMIN_USER_PERMISSION` (IN `USER_ID` BIGINT, IN `USER_TYPE` BIGINT, IN `MODULE_ID` BIGINT)   BEGIN
	SELECT 
	UP.user_permission_id
	,UP.admin_module_id
	,AM.module_name
	,UP.view_permission
	,UP.create_permission
	,UP.delete_permission
	,UP.update_permission
	
	FROM tbl_user_permission UP 
	INNER JOIN tbl_admin_module AM ON AM.admin_module_id=UP.admin_module_id AND AM.status='1'
	WHERE UP.user_id=USER_ID AND UP.user_type_id=USER_TYPE AND UP.status='1' AND UP.admin_module_id=MODULE_ID
	AND (UP.create_permission=1 OR UP.update_permission=1 OR UP.delete_permission=1 OR UP.view_permission=1);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_BARCODE` (IN `SFG_GRN_DETAIL_ID` BIGINT(20), IN `STORE_ID` BIGINT(20))   BEGIN
    SELECT 
    sfb.store_id,
    sfb.grn_type,
    sfb.box_no,
    sfb.sfg_id,
    sfgd.no_of_boxes,
    sfb.no_of_items,
    CASE 
        WHEN sfb.grn_type = 'rm' THEN rm.raw_material_name
        WHEN sfb.grn_type = 'sfg' THEN s.sfg_name
    END AS item_name,
    sfg.sfg_grn_no,
    sp.company_name
	FROM tbl_sfg_box_detail sfb
    INNER JOIN tbl_sfg_grn sfg ON sfg.sfg_grn_id = sfb.sfg_grn_id
    INNER JOIN tbl_sfg_grn_detail sfgd ON sfgd.sfg_grn_detail_id = sfb.sfg_grn_detail_id
	LEFT JOIN tbl_raw_material rm ON rm.raw_material_id = sfb.sfg_id
    INNER JOIN tbl_supplier sp ON sp.supplier_id = sfgd.supplier_id
	LEFT JOIN tbl_sfg s ON s.sfg_id = sfb.sfg_id
    WHERE (sfb.sfg_grn_detail_id = SFG_GRN_DETAIL_ID OR SFG_GRN_DETAIL_ID = 0)
    AND sfb.store_id = STORE_ID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_BARCODE_DETAIL` (IN `BOX_NO` VARCHAR(255))   BEGIN
	SELECT 
	   	bd.box_no,
	   	bd.product_id, 
       	bd.no_of_items,
       	bl.location_no,
       	tp.product_name, 
       	tp.category_id, 
       	tl.location_name,
       	tc.name 
       	FROM tbl_box_detail bd 
       	INNER JOIN tbl_box_location bl ON bd.box_no = bl.box_no 
       	INNER JOIN tbl_product tp ON bd.product_id = tp.product_id 
       	INNER JOIN tbl_category tc ON tp.category_id = tc.category_id 
       	INNER JOIN tbl_locations tl ON bl.location_no = tl.location_no
       	WHERE bd.box_no = BOX_NO;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_BOM_DETAIL` (IN `STORE_ID` BIGINT(20), IN `FG_ID` BIGINT(20))   BEGIN
	SELECT 
          b.grn_type,
          b.fg_id,
          b.item_id,
          b.quantity,
          f.fg_code,
          f.fg_discription,
          CASE 
            WHEN b.grn_type = 'rm' THEN rm.raw_material_name
            WHEN b.grn_type = 'sfg' THEN s.sfg_name
            ELSE NULL
          END AS item_name,
          CASE 
            WHEN b.grn_type = 'rm' THEN rm.raw_material_code
            WHEN b.grn_type = 'sfg' THEN s.sfg_code
            ELSE NULL
          END AS item_code
        FROM `tbl_bom` b
        LEFT JOIN tbl_sfg s ON b.item_id = s.sfg_id
        LEFT JOIN tbl_raw_material rm ON rm.raw_material_id = b.item_id
        INNER JOIN tbl_fg f ON f.fg_id = b.fg_id
        WHERE b.store_id = STORE_ID
        AND b.is_deleted = '0'
        AND (b.fg_id = FG_ID OR FG_ID = 0);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_BOM_LIST` (IN `STORE_ID` BIGINT(20), IN `FG_ID` BIGINT(20))   BEGIN
	SELECT DISTINCT
    b.fg_id,
    f.fg_code,
    f.fg_discription,
    COUNT(DISTINCT b.item_id) AS item_count,
     SUM(b.quantity) as qty
FROM tbl_bom b
INNER JOIN tbl_fg f ON f.fg_id = b.fg_id
LEFT JOIN tbl_sfg s ON b.item_id = s.sfg_id
LEFT JOIN tbl_raw_material rm ON rm.raw_material_id = b.item_id
WHERE b.store_id = STORE_ID
AND (b.fg_id = FG_ID OR FG_ID = 0)
AND b.is_deleted = '0'
GROUP BY b.fg_id, f.fg_code, f.fg_discription;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_BOX_LIST` (IN `BOX_ID` BIGINT, IN `BOX_NO` BIGINT, IN `STORE_ID` BIGINT)   BEGIN
	SELECT 
			p.product_id
	        , p.product_name
	        , p.product_source
	        , p.oem_id
	        , p.category_id
	        , p.size
	        , p.model
	        , p.unit_id
	        , p.sku
	        , p.quality
	        , p.article_code
	        , b.box_no
	        , b.no_of_items
	        , b.box_detail_id
	        , b.remaining_item
	        , gd.product_grn_detail_id
	        , gd.product_grn_id 
	        , c.name category_name
	        , tl.location_name
	        , s.company_name
	        , tu.unit
	FROM 	tbl_product p 
	INNER JOIN tbl_product_grn_detail gd ON gd.product_id = p.product_id
	INNER JOIN tbl_product_grn tpg ON tpg.product_grn_id = gd.product_grn_id
	INNER JOIN tbl_box_detail b ON b.product_grn_detail_id = gd.product_grn_detail_id
	LEFT JOIN tbl_box_location tbl ON tbl.box_no = b.box_no
	LEFT JOIN tbl_locations tl ON tl.location_no = tbl.location_no
	LEFT JOIN tbl_category c ON c.category_id = p.category_id
	LEFT JOIN tbl_supplier s ON s.supplier_id = gd.supplier_id
	LEFT JOIN  tbl_unit_of_measure tu ON tu.unit_id = p.unit_id
	WHERE	(b.box_detail_id = BOX_ID OR BOX_ID = 0)
			AND tpg.store_id = STORE_ID
			AND (b.box_no = BOX_NO OR BOX_NO = 0);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_BOX_LOCATION` (IN `BOX_LOCATION_ID` BIGINT, IN `STORE_ID` BIGINT, IN `BOX_NO` BIGINT, IN `LOCATION_NO` BIGINT)   BEGIN
	SELECT 	b.box_location_id
			, b.store_id
			, b.location_no
			, b.box_no
			, b.created_date		
	FROM 	tbl_box_location b 
	WHERE	b.store_id = STORE_ID
			AND (b.box_no = BOX_NO OR b.location_no = LOCATION_NO);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_BRANCH_LIST` (IN `CLIENT_ID` BIGINT, IN `BRANCH_ID` INT, IN `BRANCH_NAME` VARCHAR(255), IN `LEGAL_NAME` VARCHAR(255), IN `MAIN_BRANCH` ENUM('1','0'))   BEGIN
	SELECT 	l.branch_id
			, l.branch_name
			, l.legal_name
			, l.main_branch
			, l.address
			, l.sell
			, l.make
			, l.buy
			, l.status
			, l.created_date
	FROM 	tbl_branch l
	WHERE  	l.is_deleted = '0'
			AND (l.branch_name LIKE CONCAT('%',BRANCH_NAME,'%') OR BRANCH_NAME IS NULL)
			AND l.client_id = CLIENT_ID
			AND (l.branch_id = BRANCH_ID OR BRANCH_ID = 0)
						ORDER BY l.branch_name ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_CATEGORY` (IN `CATEGORY_ID` INT, IN `CATEGORY_NAME` VARCHAR(255))   BEGIN
	SELECT 
			c.category_id
			, c.name
			, c.slug category_slug
			, c.hsn_code
			, c.status
			, c.created_date
	FROM 	tbl_category c
	WHERE  	(c.name LIKE CONCAT('%',CATEGORY_NAME,'%') OR CATEGORY_NAME IS NULL)
		  	AND c.is_deleted='0' 
		  	AND (c.category_id = CATEGORY_ID OR CATEGORY_ID = 0)
	ORDER BY c.name ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_CATEGORY_WISE_PRICE` (IN `STORE_ID` BIGINT, IN `CATEGORY_ID` BIGINT)   BEGIN
	SELECT SUM(s.item_price) total_value 
		FROM
			(	SELECT (SUM(tbd.remaining_item)*tpgd.purchase_price_per_item) item_price 
				FROM tbl_product_grn_detail tpgd 
				INNER JOIN tbl_box_detail tbd ON tbd.product_grn_detail_id = tpgd.product_grn_detail_id 
				INNER JOIN tbl_box_location tbl ON tbl.box_no = tbd.box_no 
				INNER JOIN tbl_product tp ON tp.product_id = tbd.product_id
				WHERE 	tpgd.category_id = CATEGORY_ID 
						AND tbd.remaining_item > 0 
						AND tp.is_deleted = '0'
				GROUP BY tbd.product_grn_detail_id 
			) s; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_CLIENT_LIST` (IN `CLIENT_ID` BIGINT)   BEGIN
	SELECT 
			c.client_id
			, c.client_name
			, c.address
			, c.contact_person_name
			, c.contact_person_email
			, c.contact_person_mobile_no
			, c.created_date
	FROM	tbl_client c 
	WHERE 	c.status = '1' 
			AND c.is_deleted = '0'
			AND (c.client_id = CLIENT_ID OR CLIENT_ID = 0);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_COUNTRY_LIST` ()   BEGIN
	SELECT c.country_id 
		   , c.name country_name
		   , c.iso_code_2 country_code 
	FROM tbl_country c
	WHERE c.status='1';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_COUNT_RM` (IN `FG_ID` BIGINT(20), IN `STORE_ID` BIGINT(20))   BEGIN
        SELECT 
            b.bom_id,
            b.fg_id,
            b.item_id,
            b.quantity,
            b.grn_type,
            f.fg_code,
            COUNT(b.grn_type) as no_of_rm
        FROM tbl_bom b
        INNER JOIN tbl_fg f ON f.fg_id = b.fg_id
        LEFT JOIN tbl_sfg s ON s.sfg_id = b.item_id AND b.grn_type = 'sfg'
        LEFT JOIN tbl_raw_material rm ON rm.raw_material_id = b.item_id AND b.grn_type = 'rm'    
        WHERE (b.fg_id = FG_ID OR FG_ID = 0)
        AND b.store_id = STORE_ID
        GROUP BY (b.grn_type);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_COUPON_CODE_LIST` (IN `COUPON_ID` INT, IN `EXPIRY_DATE` DATE)   BEGIN
	SELECT
			CC.coupon_code_id
			, CC.title coupon_title
			, CC.code coupon_code
			, CC.discount_percent
			, CC.flat_amount
			, CC.expiry_date
			, CC.isFeatured
			, CC.status
			, CC.created_date		
	FROM tbl_coupon_code CC 
	WHERE   (CC.expiry_date >= EXPIRY_DATE OR EXPIRY_DATE IS NULL)
			AND (CC.coupon_code_id = COUPON_ID OR COUPON_ID IS NULL)
						AND isDeleted='0';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_CURRENCY_CODE_LIST` (IN `CURRENCY_ID` INT, IN `CURRENCY_CODE` VARCHAR(255))   BEGIN
	SELECT	c.currency_id
           	, c.currency_code
	FROM 	tbl_currency c
	WHERE  	c.is_deleted = '0'
			AND (c.currency_id = CURRENCY_ID OR CURRENCY_ID = 0)
			AND (c.currency_code LIKE CONCAT('%',CURRENCY_CODE,'%') OR CURRENCY_CODE IS NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_CUSTOMER_LIST` (IN `CUSTOMER_ID` BIGINT, IN `COMPANY_NAME` VARCHAR(255))   BEGIN
	SELECT  
		 	c.customer_id
			, c.first_name
			, c.last_name
			, c.company_name
			, c.display_name
			, c.email
			, c.billing_address
			, c.shipping_address
			, c.mobile_no
			, c.pan_no
			, c.gst_no
			, c.additional_comments
			, c.created_by
			, c.status
			, c.register_date	
	FROM tbl_customer c
	WHERE	c.is_deleted='0' 
			AND (c.customer_id=CUSTOMER_ID OR CUSTOMER_ID = 0)
			AND (c.company_name LIKE CONCAT('%',COMPANY_NAME,'%') OR COMPANY_NAME IS NULL)
	ORDER BY c.first_name, c.last_name DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_DEPARTMENT_LIST` (IN `DEPARTMENT_ID` INT, IN `DEPARTMENT_NAME` VARCHAR(255), IN `IS_DELETED` ENUM('1','0'))   BEGIN
	SELECT 
			d.department_id
			, d.department_name 
			, d.status
			, d.created_by
			, d.created_date
	FROM 	tbl_department d
	WHERE  	(d.department_name LIKE CONCAT('%',DEPARTMENT_NAME,'%') OR DEPARTMENT_NAME IS NULL)
		  	AND d.is_deleted='0' 
		  	AND (d.department_id = DEPARTMENT_ID OR DEPARTMENT_ID = 0)
	ORDER BY d.department_name ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_DISPATCH_REPORT` (IN `STORE_ID` BIGINT(20), IN `GENERAL_ISSUE_ID` BIGINT(20))   BEGIN
	SELECT gi.general_issues_id,
		gi.general_issues_no,
        gi.fg_id,
        gi.no_of_rm,
        gi.fg_quantity,
        gi.is_dispatch,
        f.fg_code,
        f.fg_discription
from tbl_general_issues gi 
INNER JOIN tbl_fg f ON gi.fg_id = f.fg_id 
WHERE gi.is_deleted = '0'
AND gi.store_id = STORE_ID
AND (gi.general_issues_id = GENERAL_ISSUE_ID OR GENERAL_ISSUE_ID = 0)
AND gi.is_dispatch = '1';

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_EMPLOYEE_LIST` (IN `EMPLOYEE_ID` BIGINT, IN `STORE_ID` BIGINT, IN `DEPARTMENT_ID` BIGINT, IN `EMPLOYEE_NAME` VARCHAR(255))   BEGIN
	SELECT 
		e.employee_id
		, e.department_id
		, e.employee_name
		, e.employee_email
		, e.employee_mobile_no
		, e.employee_gender
		, e.employee_address
		, e.employee_designation
		, e.employee_dob
		, e.status		
		, e.created_by
		, e.created_date
		, d.department_name
	FROM tbl_employee e 
	LEFT JOIN tbl_department d ON d.department_id = e.department_id
	WHERE	e.is_deleted = '0' 
			AND (e.employee_id=EMPLOYEE_ID OR EMPLOYEE_ID = 0)
			AND (e.store_id=STORE_ID)
			AND (e.department_id=DEPARTMENT_ID OR DEPARTMENT_ID = 0)
			AND (e.employee_name LIKE CONCAT('%',EMPLOYEE_NAME,'%') OR EMPLOYEE_NAME IS NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_FG_ALIAS_LIST` (IN `STORE_ID` BIGINT(20), IN `PRODUCT_ALIAS_ID` BIGINT(20))   BEGIN
	SELECT *
        FROM tbl_fg f
        INNER JOIN tbl_product_alias tpa ON tpa.fg_id = f.fg_id
        WHERE f.store_id = STORE_ID
        AND (tpa.product_alias_id = PRODUCT_ALIAS_ID OR PRODUCT_ALIAS_ID = 0)
        AND tpa.is_deleted = '0';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_FG_BOM_LIST` (IN `FG_ID` BIGINT, IN `STORE_ID` BIGINT)   BEGIN
    SELECT 
    b.bom_id,
    b.fg_id,
    b.item_id,
    b.quantity,
    b.grn_type,
    f.fg_code,
    CASE 
        WHEN b.grn_type = 'sfg' THEN s.sfg_name
        WHEN b.grn_type = 'rm'  THEN rm.raw_material_name
        ELSE NULL
    END AS item_name,
    GROUP_CONCAT( l.location_name ORDER BY l.location_name SEPARATOR ', ') AS location_names
FROM tbl_bom b
INNER JOIN tbl_fg f ON f.fg_id = b.fg_id
LEFT JOIN tbl_sfg_box_detail sbd ON sbd.sfg_id = b.item_id 
LEFT JOIN tbl_sfg_box_location sbl ON sbl.box_no = sbd.box_no 
LEFT JOIN tbl_locations l ON l.location_no = sbl.location_no 
LEFT JOIN tbl_sfg s ON s.sfg_id = b.item_id
LEFT JOIN tbl_raw_material rm ON rm.raw_material_id = b.item_id 
    WHERE (b.fg_id = FG_ID OR FG_ID = 0)
      AND b.store_id = STORE_ID

    GROUP BY 
        b.bom_id,
        b.fg_id,
        b.item_id,
        b.quantity,
        b.grn_type,
        f.fg_code,
        item_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_FG_LIST` (IN `FG_ID` BIGINT, IN `FG_DESCRIPTION` TEXT, IN `STORE_ID` BIGINT(20))   BEGIN
    SELECT DISTINCT f.fg_id,
			f.fg_code,
            f.store_id,
            f.sales_qty,
            f.fg_discription
            FROM tbl_fg f
	WHERE 	f.is_deleted = '0'
			AND (f.fg_id = FG_ID OR FG_ID = 0)
			AND (f.fg_discription LIKE CONCAT('%',FG_DESCRIPTION,'%') OR FG_DESCRIPTION IS NULL)
            AND f.store_id = STORE_ID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_FG_LIST_DROPDOWN` (IN `FG_ID` BIGINT(20), IN `FG_DESCRIPTION` VARCHAR(255), IN `STORE_ID` BIGINT(20), IN `IS_BOM` ENUM('0','1'))   BEGIN
	    SELECT DISTINCT f.fg_id,
			f.fg_code,
            f.store_id,
            f.sales_qty,
            f.fg_discription
            FROM tbl_fg f
	WHERE 	f.is_deleted = '0'
			AND (f.fg_id = FG_ID OR FG_ID = 0)
			AND (f.fg_discription LIKE CONCAT('%',FG_DESCRIPTION,'%') OR FG_DESCRIPTION IS NULL)
            AND f.store_id = STORE_ID
            AND (f.is_bom = IS_BOM);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_GENERAL_ISSUES_DETAIL_LIST` (IN `GENERAL_ISSUES_ID` BIGINT(20), IN `STORE_ID` BIGINT(20))   BEGIN
SELECT 
	f.fg_code,
    b.grn_type,
    CASE 
        WHEN b.grn_type = 'RM' THEN rm.raw_material_name
        WHEN b.grn_type = 'SFG' THEN s.sfg_name
        ELSE ''
    END AS item_name,
    CASE 
        WHEN b.grn_type = 'RM' THEN rm.raw_material_code
        WHEN b.grn_type = 'SFG' THEN s.sfg_code
        ELSE ''
    END AS item_code,
	b.quantity,
    gi.fg_quantity,
    b.item_id,
(SELECT sum(tpl.no_of_items) from tbl_pick_list tpl 
 where tpl.sales_order_id = gi.general_issues_id 
 and rm.raw_material_id = tpl.item_id GROUP by tpl.item_id) scanned_qty
FROM `tbl_general_issues` gi
INNER JOIN tbl_bom b ON b.fg_id = gi.fg_id
INNER JOIN tbl_fg f on f.fg_id = gi.fg_id
LEFT JOIN tbl_raw_material rm ON rm.raw_material_id = b.item_id
LEFT JOIN tbl_sfg s ON s.sfg_id = b.item_id
WHERE (gi.general_issues_id = GENERAL_ISSUES_ID OR GENERAL_ISSUES_ID = 0)
AND gi.store_id = STORE_ID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_GENERAL_ISSUES_LIST` (IN `STORE_ID` BIGINT, IN `GENERAL_ISSUE_ID` BIGINT(20))   BEGIN
	SELECT gi.general_issues_id,
		gi.general_issues_no,
        gi.fg_id,
        gi.no_of_rm,
        gi.fg_quantity,
        gi.is_dispatch,
        f.fg_code,
        f.fg_discription
from tbl_general_issues gi 
INNER JOIN tbl_fg f ON gi.fg_id = f.fg_id 
WHERE gi.is_deleted = '0'
AND gi.store_id = STORE_ID
AND (gi.general_issues_id = GENERAL_ISSUE_ID OR GENERAL_ISSUE_ID = 0);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_GENERAL_SETTING` (IN `SETTING_ID` INT)   BEGIN
	SELECT 
			s.settings_id
			, s.currency_code_id
			, s.default_delivery_time_sales_order sales_order
			, s.default_lead_time_purchase_order purchase_order
	FROM 	tbl_settings s
	WHERE 	s.settings_id = SETTING_ID;	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_GRN_LIST` (IN `STORE_ID` BIGINT, IN `IS_QUALITY_CHECKED` ENUM('1','0'))   BEGIN
	SELECT 
			g.product_grn_id
			, g.product_grn_no
			, g.created_date grn_date			
	FROM 	tbl_product_grn g 
	WHERE	g.is_deleted = '0' AND g.store_id = STORE_ID
			AND g.is_quality_checked = IS_QUALITY_CHECKED
	ORDER BY g.product_grn_id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_GRN_TYPE_LIST` (IN `GRN_TYPE_ID` BIGINT(20), IN `GRN_TYPE_NAME` VARCHAR(255), IN `CLIENT_ID` BIGINT(20))   BEGIN
	SELECT 
              gt.grn_type_id
            , gt.grn_type_name
            , gt.client_id
            , gt.status
            , gt.created_date
	FROM 	tbl_grn_type gt
	WHERE  	(gt.grn_type_name LIKE CONCAT('%',GRN_TYPE_NAME,'%') OR GRN_TYPE_NAME IS NULL) 
		  	AND (gt.grn_type_id = GRN_TYPE_ID OR GRN_TYPE_ID = 0)
			AND gt.is_deleted = '0'
	ORDER BY gt.grn_type_name ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_INVOICE_LIST` (IN `INVOICE_ID` BIGINT(20), IN `INVOICE_NAME` VARCHAR(255), IN `CLIENT_ID` BIGINT(20))   BEGIN
	SELECT 
              i.invoice_id
            , i.invoice_name
            , i.client_id
            , i.status
            , i.created_date
	FROM 	tbl_invoice i
	WHERE  	(i.invoice_name LIKE CONCAT('%',INVOICE_NAME,'%') OR INVOICE_NAME IS NULL) 
		  	AND (i.invoice_id = INVOICE_ID OR INVOICE_ID = 0)
			AND i.is_deleted = '0'
	ORDER BY i.invoice_name ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_ITEM_BARCODE` (IN `BOX_NO` BIGINT(20), IN `STORE_ID` BIGINT(20))   BEGIN
    SELECT 
    sfb.store_id,
    sfb.grn_type,
    sfb.box_no,
    sfb.sfg_id,
    sfb.no_of_items,
    sfb.remaining_item,
    sfb.sfg_id,
    sfb.sfg_box_detail_id,
    sfb.is_put_away,
    CASE 
        WHEN sfb.grn_type = 'rm' THEN rm.raw_material_name
        WHEN sfb.grn_type = 'sfg' THEN s.sfg_name
    END AS item_name
	FROM tbl_sfg_box_detail sfb
	LEFT JOIN tbl_raw_material rm ON rm.raw_material_id = sfb.sfg_id
	LEFT JOIN tbl_sfg s ON s.sfg_id = sfb.sfg_id
    WHERE (sfb.box_no = BOX_NO OR BOX_NO = 0)
    AND sfb.store_id = STORE_ID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_LOCATIONS_LIST` (IN `LOCATION_ID` BIGINT, IN `STORE_ID` BIGINT, IN `LOCATION_NAME` VARCHAR(255), IN `LOCATION_NO` BIGINT)   BEGIN
	SELECT 	l.location_id
    		, l.store_id
            , l.floor_no
            , l.room_no
            , l.rack_no
            , l.shelf_no
            , l.bin_no
            , l.location_name
            , l.location_no
            , l.location_remarks
			, l.status
			, l.created_date
			, s.store_name
			, b.branch_name
	FROM 	tbl_locations l
	LEFT JOIN tbl_stores s ON s.store_id = l.store_id
	LEFT JOIN tbl_branch b ON b.branch_id = s.branch_id
	WHERE  	(l.location_name LIKE CONCAT('%',LOCATION_NAME,'%') OR LOCATION_NAME IS NULL)
					 	AND (l.location_id = LOCATION_ID OR LOCATION_ID = 0)
		 	AND (l.location_no = LOCATION_NO OR LOCATION_NO = 0)
		 	AND l.is_deleted = '0'
		 	AND l.store_id = STORE_ID
	ORDER BY l.location_name ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_LOCATION_DETAIL` (IN `LOCATION_NO` BIGINT(20), IN `STORE_ID` BIGINT, IN `BOX_NO` VARCHAR(255))   BEGIN
      
       SELECT 
		bd.box_no,
		bd.sfg_id, 
       	bd.no_of_items,
       	bl.location_no,
      	bd.grn_type, 
       	CASE 
        	WHEN bd.grn_type = 'RM' THEN r.raw_material_name
        	WHEN bd.grn_type = 'SFG' THEN s.sfg_name
        ELSE ''
    	END AS item_name,
       	tl.location_name,
       	bd.is_put_away,
        bd.remaining_item
       FROM tbl_sfg_box_detail bd 
       INNER JOIN tbl_sfg_box_location bl ON bd.box_no = bl.box_no 
       LEFT JOIN tbl_sfg s ON s.sfg_id = bd.sfg_id
       LEFT JOIN tbl_raw_material r ON r.raw_material_id = bd.sfg_id
       INNER JOIN tbl_locations tl ON bl.location_no = tl.location_no
       WHERE (bl.location_no = LOCATION_NO OR LOCATION_NO = 0)
       AND (bd.box_no = BOX_NO OR BOX_NO = 0)
       AND (bd.store_id = STORE_ID)
       AND bd.is_put_away = '1'
       AND bd.remaining_item > 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_MODEL_LIST` (IN `MODEL_ID` BIGINT, IN `STORE_ID` BIGINT, IN `CATEGORY_ID` BIGINT, IN `MODEL_NAME` VARCHAR(255))   BEGIN
	SELECT 
		  m.model_id
		, m.store_id
		, m.category_id
		, m.model
		, m.created_by
		, m.created_date 
		, c.name category_name
	FROM 	tbl_model m
	INNER JOIN tbl_category c ON c.category_id = m.category_id
	WHERE 	(m.model_id = MODEL_ID OR MODEL_ID = 0)
			AND m.store_id = STORE_ID
			AND m.is_deleted = '0'
			AND (m.category_id = CATEGORY_ID OR CATEGORY_ID = 0)
			AND (m.model LIKE CONCAT('%',MODEL_NAME,'%') OR MODEL_NAME IS NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_OCCUPIED_LOCATION_LIST` (IN `STORE_ID` BIGINT(20))   BEGIN
	SELECT 
		tbl.location_no
		, tl.location_name 
		, GROUP_CONCAT(tbl.box_no)
		, tbd.is_put_away 
	FROM tbl_sfg_box_location tbl
	INNER JOIN tbl_locations tl ON tl.location_no = tbl.location_no 
	INNER JOIN tbl_sfg_box_detail tbd ON tbd.box_no = tbl.box_no
	INNER JOIN tbl_sfg_grn tpg ON tpg.sfg_grn_id = tbd.sfg_grn_id AND tpg.is_deleted = '0'
	WHERE 	tbd.remaining_item > 0 
			AND tbd.is_put_away = '1'
			AND (tbl.store_id = STORE_ID)
	GROUP BY tbl.location_no,tl.location_name
	ORDER BY tbl.location_no ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_OPERATIONS_LIST` (IN `OPERATION_ID` INT, IN `OPERATION_TITLE` VARCHAR(255))   BEGIN
	SELECT 
			o.operation_id
			, o.operation_title
			, o.status
			, o.created_date
	FROM 	tbl_operations o
	WHERE  	(o.operation_title LIKE CONCAT('%',OPERATION_TITLE,'%') OR OPERATION_TITLE IS NULL)		  
		  	AND (o.operation_id = OPERATION_ID OR OPERATION_ID = 0) AND o.is_deleted = '0'
	ORDER BY o.operation_title ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_PACKAGE_TYPE_LIST` (IN `PACKAGE_TYPE_ID` INT, IN `PACKAGE_TYPE` VARCHAR(255))   BEGIN
	SELECT	P.package_type_id
			, P.package_type
	FROM 	tbl_package_type P
	WHERE  	(P.package_type LIKE CONCAT('%',PACKAGE_TYPE,'%') OR PACKAGE_TYPE IS NULL)
		  	AND P.is_deleted='0' 
		  	AND (P.package_type_id = PACKAGE_TYPE_ID OR PACKAGE_TYPE_ID = 0)
	ORDER BY P.package_type ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_PENDING_GRN_LIST` (IN `STORE_ID` BIGINT)   BEGIN
	SELECT 		g.product_grn_id
			, g.product_grn_no
			, g.created_date grn_date
			, gd.no_of_boxes
			, gd.no_of_items
			, p.product_name
			, c.name category_name
			, s.company_name
			, gd.product_grn_detail_id
			-- , tbd.box_no 
	FROM 	tbl_product_grn g 
	INNER JOIN tbl_product_grn_detail gd ON gd.product_grn_id = g.product_grn_id
	-- INNER JOIN tbl_box_detail tbd ON tbd.product_grn_detail_id = gd.product_grn_detail_id 
	INNER JOIN tbl_product p ON p.product_id = gd.product_id
	LEFT JOIN tbl_category c ON c.category_id = p.category_id
	LEFT JOIN tbl_supplier s ON s.supplier_id = gd.supplier_id
	WHERE	g.store_id = STORE_ID AND g.is_quality_checked = '0'
			AND g.is_deleted = '0'
	-- AND tbd.remaining_item = tbd.no_of_items AND
	-- tbd.box_no NOT IN (SELECT box_no from tbl_box_location tbl WHERE tbl.store_id = STORE_ID)
	ORDER BY g.product_grn_id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_PENDING_PUT_AWAY` (IN `STORE_ID` BIGINT)   BEGIN
    SELECT 	
        g.sfg_grn_id,
        g.sfg_grn_no,
        g.created_date AS grn_date,
        gd.no_of_boxes,
        tbd.no_of_items,
        g.grn_type,
        CASE 
            WHEN g.grn_type = 'sfg' THEN sf.sfg_name
            WHEN g.grn_type = 'rm' THEN r.raw_material_name
            ELSE NULL
        END AS item_name,
        s.company_name,
        gd.sfg_grn_detail_id,
        tbd.box_no 
    FROM tbl_sfg_grn g 
    INNER JOIN tbl_sfg_grn_detail gd ON gd.sfg_grn_id = g.sfg_grn_id
    INNER JOIN tbl_sfg_box_detail tbd ON tbd.sfg_grn_detail_id = gd.sfg_grn_detail_id AND tbd.is_put_away = '0'
    LEFT JOIN tbl_sfg sf ON sf.sfg_id = gd.sfg_id
    LEFT JOIN tbl_raw_material r ON r.raw_material_id = gd.sfg_id
    LEFT JOIN tbl_supplier s ON s.supplier_id = gd.supplier_id
    WHERE g.store_id = STORE_ID
    ORDER BY g.sfg_grn_id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_PICK_LIST` (IN `PICK_LIST_ID` BIGINT, IN `STORE_ID` BIGINT, IN `CUSTOMER_ID` BIGINT, IN `SALES_ORDER_ID` BIGINT, IN `FROM_DATE` DATE, IN `TO_DATE` DATE)   BEGIN 
	SELECT  
			DISTINCT tpl.pick_list_id
			, tso.sales_order_no
			, tc.company_name
			, tc.first_name
			, tc.last_name
			, tp.product_name 
			, tsod.product_alias
			, tso.purchase_order_id
			, tbd.box_no 
			, tbd.box_detail_id
			, tpl.no_of_items 
			, tpl.delivery_date
			, tpl.no_of_stickers
	FROM 	tbl_pick_list tpl 
	INNER JOIN tbl_sales_order tso ON tso.sales_order_id = tpl.sales_order_id 
	INNER JOIN tbl_sales_order_detail tsod ON tsod.sales_order_id = tso.sales_order_id 
	INNER JOIN tbl_product tp ON tp.product_id = tpl.product_id 
	INNER JOIN tbl_customer tc ON tc.customer_id = tso.customer_id 
	INNER JOIN tbl_box_detail tbd ON tbd.box_detail_id = tpl.box_detail_id
	WHERE 	tpl.status = '1' AND tpl.is_deleted = '0'
			AND tbd.store_id = STORE_ID
			AND (tpl.pick_list_id = PICK_LIST_ID OR PICK_LIST_ID = 0)
			AND (tso.customer_id = CUSTOMER_ID OR CUSTOMER_ID = 0)
			AND (tpl.sales_order_id = SALES_ORDER_ID OR SALES_ORDER_ID = 0)
	ORDER BY tpl.pick_list_id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_PO_PRODUCT_LIST` (IN `STORE_ID` BIGINT, IN `PURCHASE_ORDER_ID` BIGINT)   BEGIN
	SELECT 
			tpod.purchase_order_detail_id 
			, tpod.purchase_order_id 
			, tpod.product_alias 
			, tpod.quantity 
			, tpod.price_per_unit 
			, tc.name category_name
			, tc.hsn_code 
			, tuom.unit
			, tp.product_name
	FROM 	tbl_purchase_order_detail tpod 
	INNER JOIN tbl_product tp ON tp.product_id = tpod.product_id 
	LEFT JOIN tbl_category tc ON tc.category_id = tp.category_id 
	LEFT JOIN tbl_unit_of_measure tuom ON tuom.unit_id = tp.unit_id 
	WHERE 	tpod.status = '1' 
			AND tpod.is_deleted = '0'
			AND tpod.purchase_order_id = PURCHASE_ORDER_ID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_PO_PRODUCT_LOCATION_LIST` (IN `STORE_ID` BIGINT, IN `PO_ID` BIGINT, IN `PRODUCT_ID` BIGINT)   BEGIN
	SELECT 	tpod.product_id
			, tpod.quantity
			, tp.product_name 
			, tpod.product_alias
			, tu.unit 
			, GROUP_CONCAT(tl.location_name) location_name
	FROM tbl_purchase_order_detail tpod
	INNER JOIN tbl_purchase_order tpo ON tpo.purchase_order_id = tpod.purchase_order_id 
	INNER JOIN tbl_product tp ON tp.product_id = tpod.product_id 
	INNER JOIN tbl_box_detail tbd ON tbd.product_id = tpod.product_id
	INNER JOIN tbl_box_location tbl ON tbl.box_no = tbd.box_no 
	INNER JOIN tbl_locations tl ON tl.location_no = tbl.location_no 
	LEFT JOIN  tbl_unit_of_measure tu ON tu.unit_id = tp.unit_id
	WHERE 	tpod.purchase_order_id = PO_ID
			AND tpo.store_id = STORE_ID
			AND (tpod.product_id = PRODUCT_ID OR PRODUCT_ID = 0)
	GROUP BY tpod.product_id,tpod.quantity
			,tp.product_name
			,tpod.product_alias
	ORDER BY tpod.product_id ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_PRODUCT_ALIAS_LIST` (IN `PRODUCT_ALIAS_ID` BIGINT, IN `STORE_ID` BIGINT, IN `PRODUCT_ID` BIGINT, IN `FG_ID` BIGINT)   BEGIN
       SELECT 
			pa.product_alias_id
			, pa.store_id
			, pa.fg_id
			, pa.product_alias_name
			, pa.status
			, pa.created_by
            , pa.product_alias_name
			, pa.created_date
            , f.fg_code
            , pa.supplier_id
            , s.company_name
	FROM tbl_product_alias pa
    INNER JOIN tbl_fg f ON f.fg_id = pa.fg_id
    INNER JOIN tbl_supplier s ON s.supplier_id = pa.supplier_id
	WHERE 	pa.store_id = STORE_ID
			AND (pa.product_id = PRODUCT_ID OR PRODUCT_ID = 0)
            AND (pa.fg_id = FG_ID OR FG_ID = 0)
			AND (pa.product_alias_id = PRODUCT_ALIAS_ID OR PRODUCT_ALIAS_ID = 0)
			AND pa.is_deleted = '0';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_PRODUCT_GRN_LIST` (IN `STORE_ID` BIGINT, IN `PRODUCT_GRN_ID` BIGINT, IN `PRODUCT_GRN_DETAIL_ID` BIGINT, IN `GRN_NO` VARCHAR(255), IN `PRODUCT_NAME` VARCHAR(255), IN `GRN_FROM_DATE` DATE, IN `GRN_TO_DATE` DATE, IN `IS_QUALITY_CHECKED` ENUM('1','0'))   BEGIN
	SELECT 
			g.product_grn_id
--			, g.grn_type_id 
			, g.product_grn_no
			, gd.product_grn_detail_id
			, g.created_date grn_date
			, gd.no_of_boxes
			, gd.no_of_items
			, gd.quality_checked_item
			, gd.purchase_price_per_item 
			, p.product_name
			, p.product_id
			, c.name category_name
			, s.company_name
			, tu.unit 
			, gd.product_grn_detail_id
	FROM 	tbl_product_grn g 
	INNER JOIN tbl_product_grn_detail gd ON gd.product_grn_id = g.product_grn_id
	INNER JOIN tbl_product p ON p.product_id = gd.product_id
	LEFT JOIN  tbl_unit_of_measure tu ON tu.unit_id = p.unit_id
	LEFT JOIN tbl_category c ON c.category_id = p.category_id
	LEFT JOIN tbl_supplier s ON s.supplier_id = gd.supplier_id
	WHERE	g.store_id = STORE_ID AND g.is_deleted = '0' 
			AND (g.product_grn_id = PRODUCT_GRN_ID OR PRODUCT_GRN_ID = 0)
			AND (gd.product_grn_detail_id = PRODUCT_GRN_DETAIL_ID OR PRODUCT_GRN_DETAIL_ID = 0)
			AND (g.product_grn_no = GRN_NO OR GRN_NO IS NULL)
			AND (p.product_name LIKE CONCAT('%',PRODUCT_NAME,'%') OR PRODUCT_NAME IS NULL)
			AND (g.is_quality_checked = IS_QUALITY_CHECKED)
		ORDER BY g.product_grn_id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_PRODUCT_LIST` (IN `PRODUCT_ID` BIGINT, IN `PRODUCT_NAME` VARCHAR(255), IN `OEM_ID` BIGINT, IN `CATEGORY_ID` BIGINT, IN `SKU` VARCHAR(255))   BEGIN
	SELECT 
	        p.product_id
	        , p.product_name
	        , p.product_source
	        , p.oem_id
	        , p.category_id
	        , sz.size
	        , m.model
	        , p.unit_id
	        , p.sku
	        , q.quality
	        , p.article_code
	        , p.hsn_code
	        , p.additional_info	        
	        , p.status	        
	        , p.created_by
	        , p.created_date 
	        , c.name category_name
	        , c.hsn_code category_hsn_code
	        , s.company_name
	        , u.unit
	        , m.model_id
	        , sz.size_id
	        , q.quality_id
	FROM 	tbl_product p
	LEFT JOIN tbl_category c ON c.category_id = p.category_id
	LEFT JOIN tbl_supplier s ON s.supplier_id = p.oem_id
	LEFT JOIN tbl_unit_of_measure u ON u.unit_id = p.unit_id	
	LEFT JOIN tbl_size sz ON sz.size_id = p.size	
	LEFT JOIN tbl_model m ON m.model_id = p.model	
	LEFT JOIN tbl_quality q ON q.quality_id = p.quality	
	WHERE 	p.is_deleted = '0'
			AND (p.product_id = PRODUCT_ID OR PRODUCT_ID = 0)
			AND (p.product_name LIKE CONCAT('%',PRODUCT_NAME,'%') OR PRODUCT_NAME IS NULL)
			AND (p.oem_id LIKE CONCAT('%',OEM_ID,'%') OR OEM_ID = 0)
			AND (p.category_id LIKE CONCAT('%',CATEGORY_ID,'%') OR CATEGORY_ID = 0)
			AND (p.sku LIKE CONCAT('%',SKU,'%') OR SKU IS NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_PRODUCT_STOCK` (IN `STORE_ID` BIGINT, IN `OEM_ID` BIGINT, IN `CATEGORY_ID` BIGINT, IN `PRODUCT_ID` BIGINT)   BEGIN
	SELECT 
			tp.product_id 
			, GROUP_CONCAT(tl.location_name) location_name
			, COUNT(tl.location_name)
			, tp.product_name 
			, tc.name category_name
			, sum(tbd.remaining_item) total_item
			, ts.company_name
			, (	SELECT GROUP_CONCAT(DISTINCT tc.company_name) customer_name 
				FROM tbl_customer tc 
				INNER JOIN tbl_sales_order tso ON tso.customer_id = tc.customer_id 
				INNER JOIN tbl_sales_order_detail tsod ON tsod.sales_order_id = tso.sales_order_id 
				WHERE tsod.product_id = tp.product_id
			  ) customer_name
			, SUM((tbd.remaining_item*tpgd.purchase_price_per_item)) total_value 
			, (	SELECT CONCAT(DATE_FORMAT(tso.created_date, "%Y-%m %d"), ' ',DATEDIFF(NOW(),tso.created_date),' ','days') 
				FROM tbl_sales_order tso 
				INNER JOIN tbl_sales_order_detail tsod ON tsod.sales_order_id = tso.sales_order_id 
				WHERE tsod.product_id = tp.product_id ORDER BY tso.created_date DESC LIMIT 1
			  ) last_sale_date_day
			, (	SELECT DATEDIFF(NOW(),tso.created_date) last_sale_day 
				FROM tbl_sales_order tso 
				INNER JOIN tbl_sales_order_detail tsod ON tsod.sales_order_id = tso.sales_order_id 
				WHERE tsod.product_id = tp.product_id ORDER BY tso.created_date DESC LIMIT 1
			  ) last_sale_day  
	FROM tbl_box_location tbl
	INNER JOIN tbl_box_detail tbd ON tbd.box_no = tbl.box_no 
	LEFT JOIN tbl_locations tl ON tl.location_no = tbl.location_no 
	INNER JOIN tbl_product tp ON tp.product_id = tbd.product_id 
	INNER JOIN tbl_supplier ts ON ts.supplier_id = tp.oem_id AND ts.is_oem = 1
	INNER JOIN tbl_category tc ON tc.category_id = tp.category_id 
	INNER JOIN tbl_product_grn_detail tpgd ON tpgd.product_grn_detail_id = tbd.product_grn_detail_id
	WHERE 	tbd.store_id = STORE_ID 
			AND tbd.remaining_item > 0 
			AND tp.is_deleted = '0'
			AND (tp.category_id = CATEGORY_ID OR CATEGORY_ID = 0)
			AND (tp.oem_id = OEM_ID OR OEM_ID = 0)
            AND (tp.product_id = PRODUCT_ID OR PRODUCT_ID =0)
	GROUP BY tbd.product_id
	ORDER BY tp.product_name ASC;
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_PRODUCT_STOCK_AT_GRN` (IN `STORE_ID` BIGINT, IN `OEM_ID` BIGINT, IN `CATEGORY_ID` BIGINT)   BEGIN
	SELECT 
			tp.product_id
			, tc.name category_name
			, tp.product_name
			, tpgd.no_of_items as total_item
			, ts.company_name  
	FROM tbl_product_grn tpg 
	INNER JOIN tbl_product_grn_detail tpgd ON tpgd.product_grn_id = tpg.product_grn_id 
	INNER JOIN tbl_product tp ON tp.product_id = tpgd.product_id
	INNER JOIN tbl_category tc ON tc.category_id = tp.category_id
	INNER JOIN tbl_supplier ts ON ts.supplier_id = tp.oem_id AND ts.is_oem = '1'
	WHERE tpg.is_quality_checked = '0' AND tpg.store_id = STORE_ID
			AND (tp.category_id = CATEGORY_ID OR CATEGORY_ID = 0)
			AND (tp.oem_id = OEM_ID OR OEM_ID = 0)
	ORDER BY tp.product_name ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_PURCHASE_ORDER_LIST` (IN `STORE_ID` BIGINT, IN `PURCHASE_ORDER_ID` BIGINT, IN `PURCHASE_ORDER_NO` VARCHAR(255), IN `CUSTOMER_ID` BIGINT, IN `ORDER_FROM_DATE` DATE, IN `ORDER_TO_DATE` DATE)   BEGIN
	SELECT  
			p.purchase_order_id
			, p.store_id
			, p.purchase_order_no
			, p.po_date
			, p.customer_id
			, p.billing_address
			, p.shipping_address
			, p.shipping_description
			, p.shipping_cost
			, p.tax_id
			, p.additional_info
			, p.created_by
			, p.created_date
			, c.first_name
			, c.last_name
			, c.company_name
			, c.billing_address
			, c.shipping_address
			, c.email
	FROM 	tbl_purchase_order p 
	LEFT JOIN tbl_customer c ON c.customer_id = p.customer_id
	WHERE	p.store_id = STORE_ID
			AND (p.purchase_order_id = PURCHASE_ORDER_ID OR PURCHASE_ORDER_ID = 0)
			AND (p.customer_id = CUSTOMER_ID OR CUSTOMER_ID = 0)
			AND (p.purchase_order_no = PURCHASE_ORDER_NO OR PURCHASE_ORDER_NO IS NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_PUT_AWAY_LIST` (IN `STORE_ID` BIGINT)   BEGIN
	SELECT tbl.box_no
			, tbl.location_no
			, tl.location_name
			, tp.product_name 
			, tc.name category_name
			, tpgd.mfg_date 
			, tpgd.expiry_date 
			, tpgd.bill_type
			, tpgd.bill_no 
			, tpg.product_grn_no 
			, tpgd.product_grn_detail_id
			, tbl.created_date put_away_date
	FROM tbl_box_location tbl 
	INNER JOIN tbl_box_detail tbd ON tbd.box_no = tbl.box_no 
	INNER JOIN tbl_product_grn_detail tpgd ON tpgd.product_grn_detail_id = tbd.product_grn_detail_id 
	INNER JOIN tbl_product_grn tpg ON tpg.product_grn_id = tbd.product_grn_id
	INNER JOIN tbl_locations tl ON tl.location_no = tbl.location_no 
	INNER JOIN tbl_product tp ON tp.product_id = tbd.product_id 
	LEFT JOIN tbl_category tc ON tc.category_id = tp.category_id
	WHERE tbl.store_id = STORE_ID AND tpg.store_id = STORE_ID;	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_QUALITY_LIST` (IN `QUALITY_ID` BIGINT, IN `STORE_ID` BIGINT, IN `CATEGORY_ID` BIGINT, IN `QUALITY_NAME` VARCHAR(255))   BEGIN
	SELECT 
		  q.quality_id
		, q.store_id
		, q.category_id
		, q.quality
		, q.created_by
		, q.created_date 
		, c.name category_name
	FROM 	tbl_quality q
	INNER JOIN tbl_category c ON c.category_id = q.category_id
	WHERE 	(q.quality_id = QUALITY_ID OR QUALITY_ID = 0)
			AND q.store_id = STORE_ID
            AND q.is_deleted = '0'
			AND (q.category_id = CATEGORY_ID OR CATEGORY_ID = 0)
			AND (q.quality LIKE CONCAT('%',QUALITY_NAME,'%') OR QUALITY_NAME IS NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_RAW_MATERIAL_LIST` (IN `RAW_MATERIAL_ID` BIGINT(20), IN `RAW_MATERIAL_NAME` VARCHAR(255), IN `CATEGORY_ID` BIGINT(20), IN `STORE_ID` BIGINT(20))   BEGIN
SELECT rm.raw_material_id,
		rm.raw_material_name,
        rm.raw_material_code,
        rm.min_level,
        rm.category_id,
        rm.size,
        rm.hsn_code,
        rm.store_id,
        rm.max_level,
        rm.inward_unit_id,
        rm.outward_unit_id,
        rm.wastage,
        rm.additional_info,
        rm.sustainability_score,
        rm.weight,
        c.name category_name,
        sz.size_id,
        um.unit,
        rm.unit_id,
        sz.size
	FROM tbl_raw_material rm
    LEFT JOIN tbl_category c ON c.category_id = rm.category_id
	LEFT JOIN tbl_size sz ON sz.size_id = rm.size	
    LEFT JOIN tbl_unit_of_measure um ON um.unit_id = rm.unit_id
	WHERE 	rm.is_deleted = '0'
			AND (rm.raw_material_id = RAW_MATERIAL_ID OR RAW_MATERIAL_ID = 0)
			AND (rm.raw_material_name LIKE CONCAT('%',RAW_MATERIAL_NAME,'%') OR RAW_MATERIAL_NAME IS NULL)
            AND rm.store_id = STORE_ID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_RM_BOX_DETAIL` (IN `BOX_ID` BIGINT(20), IN `BOX_NO` BIGINT(20), IN `STORE_ID` BIGINT(20))   BEGIN
		SELECT 
        sf.sfg_id,
		sf.sfg_name,
        sf.sfg_code,
        b.box_no,
	    b.no_of_items,
	    b.sfg_box_detail_id,
	    b.remaining_item,
        gd.sfg_grn_detail_id,
	    gd.sfg_grn_id,
	    tl.location_name,
        b.grn_type,
	    s.company_name
	FROM tbl_sfg sf
	INNER JOIN tbl_sfg_grn_detail gd ON gd.sfg_id = sf.sfg_id
	INNER JOIN tbl_sfg_grn tpg ON tpg.sfg_grn_id = gd.sfg_grn_id
	INNER JOIN tbl_sfg_box_detail b ON b.sfg_grn_detail_id = gd.sfg_grn_detail_id
	LEFT JOIN tbl_sfg_box_location tbl ON tbl.box_no = b.box_no
	LEFT JOIN tbl_locations tl ON tl.location_no = tbl.location_no
	LEFT JOIN tbl_supplier s ON s.supplier_id = gd.supplier_id
	WHERE	(b.sfg_box_detail_id = BOX_ID OR BOX_ID = 0)
			AND tpg.store_id = STORE_ID
			AND (b.box_no = BOX_NO OR BOX_NO = 0);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_RM_BOX_LOCATION` (IN `RM_BOX_LOCATION_ID` BIGINT(20), IN `STORE_ID` BIGINT(20), IN `BOX_NO` BIGINT(20), IN `LOCATION_NO` BIGINT(20))   BEGIN
        SELECT  
        b.sfg_box_location_id,
        b.store_id,
        b.location_no,
        b.box_no,
        b.created_date,
        b.grn_type,
        sbd.sfg_id,
        l.location_name,
        CASE 
            WHEN b.grn_type = 'sfg' THEN s.sfg_name
            WHEN b.grn_type = 'rm' THEN r.raw_material_name
            ELSE NULL
        END AS item_name,
        CASE 
            WHEN b.grn_type = 'sfg' THEN s.sfg_code
            WHEN b.grn_type = 'rm' THEN r.raw_material_code
            ELSE NULL
        END AS item_code
    FROM tbl_sfg_box_location b 
    INNER JOIN tbl_sfg_box_detail sbd ON sbd.box_no = b.box_no
    INNER JOIN tbl_locations l ON l.location_no = b.location_no
    LEFT JOIN tbl_sfg s ON s.sfg_id = sbd.sfg_id
    LEFT JOIN tbl_raw_material r ON r.raw_material_id = sbd.sfg_id
	WHERE	b.store_id = STORE_ID
			AND (b.box_no = BOX_NO OR BOX_NO = 0 OR b.location_no = LOCATION_NO OR LOCATION_NO = 0);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_RM_GRN_LIST` (IN `STORE_ID` BIGINT(20), IN `RM_GRN_ID` BIGINT(20), IN `RM_GRN_DETAIL_ID` BIGINT(20), IN `RM_GRN_NO` BIGINT(20), IN `RAW_MATERIAL_NAME` VARCHAR(255), IN `RM_GRN_DATE` DATE, IN `IS_RM_QUALITY_CHECKED` ENUM('1','0'))   SELECT 
			g.rm_grn_id
			, g.rm_grn_no
			, gd.rm_grn_detail_id
			, g.created_date grn_date
			, gd.no_of_boxes
			, gd.quantity
			, gd.rm_quality_checked_item
			, gd.purchase_price_per_item
			, p.raw_material_name
			, p.raw_material_id
			, c.name category_name
			, s.company_name
			, p.outward_unit_id
            , tu.unit
			, gd.rm_grn_detail_id
	FROM 	tbl_rm_grn g 
	INNER JOIN tbl_rm_grn_detail gd ON gd.rm_grn_id = g.rm_grn_id
	INNER JOIN tbl_raw_material p ON p.raw_material_id = gd.rm_id
	LEFT JOIN  tbl_unit_of_measure tu ON tu.unit_id = p.outward_unit_id
	LEFT JOIN tbl_category c ON c.category_id = p.category_id
	LEFT JOIN tbl_supplier s ON s.supplier_id = g.supplier_id
	WHERE	g.store_id = STORE_ID AND g.is_deleted = '0' 
			AND (g.rm_grn_id = RM_GRN_ID OR RM_GRN_ID = 0)
			AND (gd.rm_grn_detail_id = RM_GRN_DETAIL_ID OR RM_GRN_DETAIL_ID = 0)
			AND (g.rm_grn_no = RM_GRN_NO OR RM_GRN_NO IS NULL)
			AND (p.raw_material_name LIKE CONCAT('%',RAW_MATERIAL_NAME,'%') OR RAW_MATERIAL_NAME IS NULL)
			AND (g.is_rm_quality_checked = IS_RM_QUALITY_CHECKED)
		ORDER BY g.rm_grn_id DESC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_SALES_ORDER_LIST` (IN `STORE_ID` BIGINT, IN `SALES_ORDER_ID` BIGINT, IN `SALES_ORDER_NO` VARCHAR(255), IN `CUSTOMER_ID` BIGINT, IN `ORDER_FROM_DATE` DATE, IN `ORDER_TO_DATE` DATE)   BEGIN
	SELECT  
			s.sales_order_id
			, s.store_id
			, s.sales_order_no
			, s.purchase_order_id
			, s.po_date
			, s.customer_id
			, s.customer_reference_no
			, s.billing_address
			, s.shipping_address
			, s.shipping_description
			, s.shipping_cost
			, s.tax_id
			, s.order_date
			, s.delivery_date
			, s.additional_info
			, s.sales_order_status_id
			, s.created_by
			, s.created_date
			, c.first_name
			, c.last_name
			, c.company_name
			, c.billing_address
			, c.shipping_address
			, c.email
			, s.sales_type_id
	FROM 	tbl_sales_order s 
				LEFT JOIN tbl_customer c ON c.customer_id = s.customer_id
	WHERE	s.store_id = STORE_ID
			AND (s.sales_order_id = SALES_ORDER_ID OR SALES_ORDER_ID = 0)
			AND (s.customer_id = CUSTOMER_ID OR CUSTOMER_ID = 0)
			AND (s.sales_order_no = SALES_ORDER_NO OR SALES_ORDER_NO IS NULL)			 
	ORDER BY s.sales_order_id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_SALES_ORDER_PRODUCT_LIST` (IN `STORE_ID` BIGINT, IN `SALES_ORDER_ID` BIGINT, IN `SALES_TYPE_ID` BIGINT(20))   BEGIN
	SELECT 
			tsod.sales_order_detail_id 
			, tsod.sales_order_id 
			, tsod.product_alias 
			, tsod.quantity 
			, tc.category_id
			, tsod.price_per_unit 
			, tc.name 
			, tc.hsn_code 
			, tuom.unit
			, tp.product_id
			, tp.product_name 
	FROM 	tbl_sales_order_detail tsod 
	INNER JOIN tbl_product tp ON tp.product_id = tsod.product_id 
	LEFT JOIN tbl_category tc ON tc.category_id = tp.category_id 
	LEFT JOIN tbl_unit_of_measure tuom ON tuom.unit_id = tp.unit_id 
	WHERE 	tsod.status = '1' 
			AND tsod.is_deleted = '0'
			AND tsod.sales_order_id = SALES_ORDER_ID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_SALES_TYPE_LIST` (IN `SALES_TYPE_ID` BIGINT(20), IN `SALES_TYPE_NAME` VARCHAR(255), IN `CLIENT_ID` BIGINT(20))   BEGIN
	SELECT 
              st.sales_type_id
            , st.sales_type_name
            , st.client_id
            , st.status
            , st.created_date
	FROM 	tbl_sales_type st
	WHERE  	(st.sales_type_name LIKE CONCAT('%',SALES_TYPE_NAME,'%') OR SALES_TYPE_NAME IS NULL) 
		  	AND (st.sales_type_id = SALES_TYPE_ID OR SALES_TYPE_ID = 0)
        AND is_deleted ='0'
	ORDER BY st.sales_type_name ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_SCAN_DETAIL` (IN `GENERAL_ISSUES_ID` BIGINT(20), IN `STORE_ID` BIGINT(20))   BEGIN
SELECT 
	f.fg_code,
    pl.grn_type,
    CASE 
        WHEN pl.grn_type = 'RM' THEN rm.raw_material_name
        WHEN pl.grn_type = 'SFG' THEN s.sfg_name
        ELSE ''
    END AS item_name,
    pl.no_of_items,
    pl.sfg_box_detail_id,
    sbd.box_no
FROM `tbl_general_issues` gi
INNER JOIN tbl_pick_list pl ON pl.sales_order_id = gi.general_issues_id
INNER JOIN tbl_fg f on f.fg_id = gi.fg_id
LEFT JOIN tbl_sfg_box_detail sbd ON sbd.sfg_box_detail_id = pl.sfg_box_detail_id
LEFT JOIN tbl_raw_material rm ON rm.raw_material_id = pl.item_id
LEFT JOIN tbl_sfg s ON s.sfg_id = pl.item_id
WHERE (gi.general_issues_id = GENERAL_ISSUES_ID OR GENERAL_ISSUES_ID = 0)
AND gi.store_id = STORE_ID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_SCRAP_LIST` (IN `SCRAP_ID` BIGINT, IN `SCRAP_QUANTITY` BIGINT, IN `QUANTITY` BIGINT, IN `IS_DELETED` ENUM('0','1'), IN `BOX_NO` BIGINT)   BEGIN
	SELECT 
		  sl.scrap_id
		, sl.scrap_box_no
        , sl.scrap_qty
		, sl.remaining_item
		, sl.remark
		, sl.created_by
		, sl.created_date 
        , sl.is_deleted
	FROM 	tbl_scrap_list sl
	WHERE sl.is_deleted = '0'	
    AND (sl.scrap_id = SCRAP_ID OR SCRAP_ID = 0);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_SFG_GRN_LIST` (IN `STORE_ID` BIGINT(20), IN `SFG_GRN_ID` BIGINT(20), IN `SFG_GRN_DETAIL_ID` BIGINT(20), IN `SFG_GRN_NO` BIGINT(20), IN `SFG_NAME` VARCHAR(255), IN `SFG_GRN_DATE` DATE)   BEGIN
        SELECT 
            g.sfg_grn_id,
            g.sfg_grn_no,
            gd.sfg_grn_detail_id,
            g.created_date AS grn_date,
            gd.no_of_boxes,
            gd.quantity,
            gd.sfg_quality_checked_item,
            gd.purchase_price_per_item,
            gd.sfg_id,
            c.name AS category_name,
            s.company_name,
            g.grn_type,
            CASE 
                WHEN g.grn_type = 'rm'  THEN r.raw_material_name
                WHEN g.grn_type = 'sfg' THEN p.sfg_name
                ELSE NULL
            END AS item_name,
            CASE 
                WHEN g.grn_type = 'rm'  THEN r.raw_material_code
                WHEN g.grn_type = 'sfg' THEN p.sfg_code
                ELSE NULL
            END AS item_code
        FROM tbl_sfg_grn g
        INNER JOIN tbl_sfg_grn_detail gd ON gd.sfg_grn_id = g.sfg_grn_id
        LEFT JOIN tbl_sfg p  ON p.sfg_id = gd.sfg_id
        LEFT JOIN tbl_raw_material r ON r.raw_material_id = gd.sfg_id
        LEFT JOIN tbl_category c ON c.category_id = p.category_id
        LEFT JOIN tbl_supplier s ON s.supplier_id = gd.supplier_id
		WHERE	g.store_id = STORE_ID AND g.is_deleted = '0' 
			AND (g.sfg_grn_id = SFG_GRN_ID OR SFG_GRN_ID = 0)
			AND (gd.sfg_grn_detail_id = SFG_GRN_DETAIL_ID OR SFG_GRN_DETAIL_ID = 0)
			AND (g.sfg_grn_no = SFG_GRN_NO OR SFG_GRN_NO IS NULL)
			AND (p.sfg_name LIKE CONCAT('%',SFG_NAME,'%') OR SFG_NAME IS NULL)
			
		ORDER BY g.sfg_grn_id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_SFG_LIST` (IN `SFG_ID` BIGINT(20), IN `SFG_NAME` VARCHAR(255), IN `CATEGORY_ID` BIGINT(20), IN `STORE_ID` BIGINT(20))   BEGIN
    SELECT s.sfg_id,
		s.sfg_name,
        s.sfg_code,
        s.category_id,
        s.hsn_code,
        s.store_id,
        s.additional_info,
        s.sustainability_score,
        s.weight,
        c.name category_name
	FROM tbl_sfg s
    LEFT JOIN tbl_category c ON c.category_id = s.category_id
	WHERE 	s.is_deleted = '0'
			AND (s.sfg_id = SFG_ID OR SFG_ID = 0)
			AND (s.sfg_name LIKE CONCAT('%',SFG_NAME,'%') OR SFG_NAME IS NULL)
            AND s.store_id = STORE_ID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_SIZE_LIST` (IN `SIZE_ID` BIGINT, IN `STORE_ID` BIGINT, IN `CATEGORY_ID` BIGINT, IN `SIZE_NAME` VARCHAR(255))   BEGIN
	SELECT 
		  s.size_id
		, s.store_id
		, s.category_id
		, s.size
		, s.created_by
		, s.created_date 
		, c.name category_name
	FROM 	tbl_size s
	LEFT JOIN tbl_category c ON c.category_id = s.category_id
	WHERE 	(s.size_id = SIZE_ID OR SIZE_ID = 0)
			AND s.store_id = STORE_ID
			AND s.is_deleted = '0'
			AND (s.category_id = CATEGORY_ID OR CATEGORY_ID = 0)
			AND (s.size LIKE CONCAT('%',SIZE_NAME,'%') OR SIZE_NAME IS NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_SO_PRODUCT_LOCATION_LIST` (IN `STORE_ID` BIGINT, IN `SALES_ORDER_ID` BIGINT, IN `PRODUCT_ID` BIGINT)   BEGIN	
	SELECT 	tsod.product_id
			, tsod.quantity
			, tp.product_name
			, tsod.product_alias
			, tu.unit 
			, GROUP_CONCAT(tl.location_name) location_name
	FROM tbl_sales_order_detail tsod
	INNER JOIN tbl_sales_order tso ON tso.sales_order_id = tsod.sales_order_id 
	INNER JOIN tbl_product tp ON tp.product_id = tsod.product_id 
	INNER JOIN tbl_box_detail tbd ON tbd.product_id = tsod.product_id
	INNER JOIN tbl_box_location tbl ON tbl.box_no = tbd.box_no 
	INNER JOIN tbl_locations tl ON tl.location_no = tbl.location_no 
	LEFT JOIN  tbl_unit_of_measure tu ON tu.unit_id = tp.unit_id
	WHERE 	tsod.sales_order_id = SALES_ORDER_ID
			AND tso.store_id = STORE_ID
			AND (tsod.product_id = PRODUCT_ID OR PRODUCT_ID = 0)
	GROUP BY tsod.product_id,tsod.quantity
			,tp.product_name
			,tsod.product_alias
	ORDER BY tsod.product_id ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_STATE_LIST` (IN `COUNTRY_ID` INT, IN `STATE_NAME` VARCHAR(255))   BEGIN		
	SELECT
			 s.state_id 
			,s.name state_name
			,s.code state_code 
	FROM 	tbl_state s 
	WHERE   (s.country_id=COUNTRY_ID OR COUNTRY_ID IS NULL)
			AND (s.name LIKE CONCAT('%', STATE_NAME, '%') OR STATE_NAME IS NULL) 
			AND s.status='1'
	ORDER BY s.name ASC;		
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_STORE_DETAIL_LIST` ()   BEGIN
	SELECT 
			s.`store_detail_id`
			, s.`store_id`
			, s.`no_of_floor`
			, s.`no_of_room`
			, s.`no_of_rack`
			, s.`no_of_shelf`
			, s.`no_of_bin`
			, s.`status`
			, `created_date` 
	FROM `tbl_store_detail` s
	WHERE s.`store_detail_id` = 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_STORE_LIST` (IN `STORE_ID` BIGINT, IN `BRANCH_ID` BIGINT, IN `STORE_NAME` VARCHAR(255))   BEGIN
	SELECT 
		S.store_id
		, S.branch_id
		, S.store_name
		, S.status
		, S.created_by
		, S.created_date
		, B.branch_name
	FROM tbl_stores S 
	INNER JOIN tbl_branch B ON B.branch_id = S.branch_id
	WHERE	S.is_deleted='0' 
			AND (S.store_id=STORE_ID OR STORE_ID = 0)
			AND (S.branch_id=BRANCH_ID OR BRANCH_ID = 0)
			AND (S.store_name LIKE CONCAT('%',STORE_NAME,'%') OR STORE_NAME IS NULL)
	ORDER BY S.STORE_NAME DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_SUBCATEGORY_LIST` (IN `SUBCATEGORY_ID` INT, IN `STORE_ID` INT, IN `CATEGORY_ID` INT, IN `SUBCATEGORY_NAME` INT(255))   BEGIN
	SELECT 
		  sc.subcategory_id
		, sc.store_id
		, sc.category_id
		, sc.subcategory
		, sc.created_by
		, sc.created_date 
		, c.name category_name
	FROM 	tbl_subcategory sc
	INNER JOIN tbl_category c ON c.category_id = sc.category_id
	WHERE 	(sc.subcategory_id = SUBCATEGORY_ID OR SUBCATEGORY_ID = 0)
			AND sc.store_id = STORE_ID
			AND sc.is_delete = '0'
			AND (sc.category_id = CATEGORY_ID OR CATEGORY_ID = 0)
			AND (sc.subcategory LIKE CONCAT('%',SUBCATEGORY_NAME ,'%') OR SUBCATEGORY_NAME IS NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_SUPPLIER_LIST` (IN `SUPPLIER_ID` BIGINT, IN `COMPANY_NAME` VARCHAR(255), IN `IS_OEM` ENUM('1','0'))   BEGIN
	SELECT 
			s.supplier_id
			, s.company_name
			, s.email
			, s.mobile_no
			, s.address
			, s.pan_no
			, s.gst_no
			, s.is_oem
			, s.additional_comments
			, s.created_by
			, s.status
			, s.register_date
	FROM	tbl_supplier s
	WHERE 	s.is_deleted = '0'
			AND (s.supplier_id = SUPPLIER_ID OR SUPPLIER_ID = 0)
			AND s.is_oem = IS_OEM
			AND (s.company_name LIKE CONCAT('%',COMPANY_NAME,'%') OR COMPANY_NAME IS NULL)
	ORDER BY s.company_name ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_TAX_RATE_LIST` (IN `TAX_ID` BIGINT)   BEGIN
	SELECT 
			t.tax_id
			, t.tax_name
			, t.tax_rate
			, t.status
			, t.created_by
			, t.created_date
	FROM tbl_tax t
	WHERE (t.TAX_id = TAX_ID OR TAX_ID = 0) AND t.is_deleted = '0'
	ORDER BY t.tax_name ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_TOTAL_STOCK_VALUE` (IN `STORE_ID` BIGINT)   BEGIN
        SELECT 
            sbd.sfg_id,
            sbd.grn_type,
            CASE 
                WHEN sbd.grn_type = 'rm' THEN rm.raw_material_name
                WHEN sbd.grn_type = 'sfg' THEN s.sfg_name
                WHEN sbd.grn_type = 'fg' THEN f.fg_code
            END AS name,
            COUNT(sbd.box_no) AS number_of_boxes,
            SUM(sbd.no_of_items) AS total_item,
            SUM(sbd.remaining_item) AS remaining_item,
            GROUP_CONCAT(DISTINCT l.location_name ORDER BY l.location_name) AS locations
        FROM tbl_sfg_box_detail sbd
        INNER JOIN tbl_sfg_box_location sbl ON sbl.box_no = sbd.box_no
        LEFT JOIN tbl_locations l ON l.location_no = sbl.location_no
        LEFT JOIN tbl_raw_material rm ON rm.raw_material_id = sbd.sfg_id
        LEFT JOIN tbl_sfg s ON s.sfg_id = sbd.sfg_id
        LEFT JOIN tbl_fg f ON f.fg_id = sbd.sfg_id
        WHERE sbd.store_id = STORE_ID
        GROUP BY sbd.sfg_id, sbd.grn_type;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_TROLLEY_TYPE_LIST` (IN `TROLLEY_TYPE_ID` INT, IN `TROLLEY_TYPE` VARCHAR(255))   BEGIN
	SELECT	T.trolley_type_id
			, T.trolley_type
	FROM 	tbl_trolley_type T
	WHERE  	(T.trolley_type LIKE CONCAT('%',TROLLEY_TYPE,'%') OR TROLLEY_TYPE IS NULL)
		  	AND T.is_deleted='0' 
		  	AND (T.trolley_type_id = TROLLEY_TYPE_ID OR TROLLEY_TYPE_ID = 0)
	ORDER BY T.trolley_type ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_UNIT_CONVERSION_LIST` (IN `CLIENT_ID` BIGINT, IN `UNIT_CONVERSION_ID` BIGINT)   BEGIN
	SELECT	uc.unit_conversion_id
			, uc.from_unit_id
			, um.unit AS from_unit_text
			, uc.from_unit_value 
			, uc.to_unit_id
			, um2.unit AS to_unit_text
			, uc.to_unit_value
			, uc.created_date
	FROM tbl_unit_conversion uc 
	INNER JOIN tbl_unit_of_measure um ON um.unit_id = uc.from_unit_id
	INNER JOIN tbl_unit_of_measure um2 ON um2.unit_id = uc.to_unit_id
	WHERE 	uc.is_deleted = '0' 
			AND uc.client_id = CLIENT_ID 
			AND (uc.unit_conversion_id = UNIT_CONVERSION_ID OR UNIT_CONVERSION_ID = 0);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_UNIT_OF_MEASURE_LIST` (IN `UNIT_ID` INT, IN `UNIT` VARCHAR(255))   BEGIN
	SELECT 
			u.unit_id
			, u.unit
			, u.status
			, u.created_date
	FROM 	tbl_unit_of_measure u
	WHERE  	u.is_deleted = '0'
			AND (u.unit LIKE CONCAT('%',UNIT,'%') OR UNIT IS NULL)		  
		  	AND (u.unit_id = UNIT_ID OR (UNIT_ID = 0))
	ORDER BY u.unit ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_USER_LIST` (IN `USER_ID` INT, IN `NAME` VARCHAR(255), IN `EMAIL` VARCHAR(255))   BEGIN	
	SELECT 
			 U.user_id
			,U.name
			,U.email
			,U.mobile_no
			,U.city
			,U.dob
			,U.address
			,U.register_date
			,U.status
			,U.user_type_id
			,UT.user_type
			,(SELECT COUNT(1) FROM tbl_user_permission UP WHERE UP.user_id=U.user_id AND UP.user_type_id=8) permission
	FROM 	tbl_user U
	INNER JOIN tbl_user_type UT ON UT.user_type_id = U.user_type_id
	WHERE 	U.is_deleted='0' 
			AND (U.user_id=USER_ID OR USER_ID IS NULL)
			AND (U.name LIKE CONCAT('%',NAME,'%') OR NAME IS NULL)
			AND (U.email LIKE CONCAT('%',EMAIL,'%') OR EMAIL IS NULL)
	ORDER BY U.name ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_USER_LOGIN` (IN `EMAIL` VARCHAR(200))   BEGIN	
	IF EXISTS(SELECT 1 FROM tbl_login l WHERE l.email=EMAIL) THEN
		BEGIN
	     SELECT   u.user_id
			     ,u.name
			     ,u.email
			     ,u.client_id
			     ,u.user_type_id
			     ,l.password
			     ,l.login_id
			     ,u.mobile_no
			     ,ut.user_type
			     ,c.client_name			     
			     ,'' as message 
			     ,1 action
	     FROM tbl_user u
		 INNER JOIN tbl_login l ON l.user_id = u.user_id
	     INNER JOIN tbl_user_type ut ON ut.user_type_id =u.user_type_id
	     INNER JOIN tbl_client c ON c.client_id = u.client_id
	     WHERE u.email=EMAIL AND u.status='1' AND l.isActive='1' AND c.status='1' AND c.is_deleted='0';
		END;
	ELSE 
		BEGIN
			SELECT "Invalid login" as message, 0 action;
		END; 
	END IF;
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_VARIANT_LIST` (IN `VARIANT_ID` BIGINT, IN `VARIANT_TITLE` VARCHAR(255))   BEGIN
	SELECT 
			v.variant_id
			, v.variant_title
			, v.slug
			, v.variant_option
			, v.status
			, v.variant_required
			, v.created_by
			, v.created_date 
	FROM 	tbl_variant v 
	WHERE	v.is_deleted = '0'
			AND (v.variant_title LIKE CONCAT('%',VARIANT_TITLE,'%') OR VARIANT_TITLE IS NULL)
			AND (v.variant_id = VARIANT_ID OR VARIANT_ID = 0)
	ORDER BY v.order_by ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_BOM` (IN `STORE_ID` BIGINT(20), IN `GRN_TYPE` VARCHAR(255), IN `FG_ID` BIGINT(20), IN `ITEM_ID` BIGINT(20), IN `QUANTITY` BIGINT(20), IN `CREATED_BY` INT(11), IN `IS_DELETED` ENUM('1','0'))   BEGIN
    IF IS_DELETED <> '0' THEN
        BEGIN
            UPDATE tbl_bom b 
            SET    
                b.is_deleted = IS_DELETED
            WHERE b.fg_id = FG_ID;
            UPDATE tbl_fg f 
            SET    
                f.is_bom = '0'
            WHERE f.fg_id = FG_ID;
        
            SELECT  
                b.fg_id,         
                'Bom deleted successfully!' AS message,
                1 AS action
            FROM tbl_bom b
            WHERE b.fg_id = FG_ID;
        END;
    ELSE
        BEGIN
            INSERT INTO tbl_bom(store_id, grn_type, fg_id, item_id, quantity, created_by)
            VALUES (STORE_ID, GRN_TYPE, FG_ID, ITEM_ID, QUANTITY, CREATED_BY);
            
            SELECT
                LAST_INSERT_ID() AS bom_id,
                'BOM material added successfully!' AS message,
                1 AS action;   

            UPDATE tbl_fg f 
            SET f.is_bom = '1' 
            WHERE f.fg_id = FG_ID;
        END;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_BOX_DETAIL` (IN `STORE_ID` BIGINT, IN `PRODUCT_ID` BIGINT, IN `PRODUCT_GRN_ID` BIGINT, IN `PRODUCT_GRN_DETAIL_ID` BIGINT, IN `BOX_NAME` VARCHAR(255), IN `NO_OF_ITEMS` BIGINT, IN `CREATED_BY` BIGINT)   BEGIN	
	INSERT INTO tbl_box_detail (store_id, product_id, product_grn_id, product_grn_detail_id, box_name, no_of_items, remaining_item, created_by) 
	VALUES(STORE_ID, PRODUCT_ID, PRODUCT_GRN_ID, PRODUCT_GRN_DETAIL_ID, BOX_NAME, NO_OF_ITEMS, NO_OF_ITEMS, CREATED_BY);

	UPDATE tbl_box_detail b SET b.box_no = LAST_INSERT_ID()+10000 WHERE b.box_detail_id = LAST_INSERT_ID();

	SELECT 	 b.box_detail_id
			, b.product_grn_id
			, b.product_grn_detail_id
			, b.box_name
			, b.box_no
			, b.no_of_items
			, b.created_by
			, b.created_date					
			, 'Box detail save successfully' AS message
			, 1 action
	FROM tbl_box_detail b
	WHERE b.box_detail_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_BOX_LOCATION` (IN `STORE_ID` BIGINT, IN `LOCATION_NO` BIGINT, IN `BOX_NO` BIGINT, IN `CREATED_BY` BIGINT)   BEGIN
	IF EXISTS(SELECT 1 FROM tbl_box_location b WHERE b.box_no = BOX_NO AND b.store_id = STORE_ID) THEN 
  		BEGIN 
	    	SELECT  b.box_location_id
	        		, 'Put away process already completed for this box!' AS message
	        		, 0 action
		    FROM  	tbl_box_location b
	    	WHERE 	b.box_no = BOX_NO AND b.store_id = STORE_ID;
		END; 
	ELSE
    	BEGIN
			INSERT INTO tbl_box_location(store_id, location_no, box_no, created_by) 
			VALUES (STORE_ID, LOCATION_NO, BOX_NO, CREATED_BY);
			
			UPDATE tbl_box_detail tbd SET tbd.is_put_away = '1' WHERE tbd.box_no = BOX_NO; 
		
			SELECT  b.box_location_id
	        		, 'Put away process completed for this box!' AS message
	        		, 1 action
		    FROM  	tbl_box_location b
	    	WHERE 	b.box_no = BOX_NO AND b.store_id = STORE_ID;
		END;
	END IF;	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_BRANCH` (IN `BRANCH_ID` BIGINT, IN `CLIENT_ID` BIGINT, IN `BRANCH_NAME` VARCHAR(255), IN `LEGAL_NAME` VARCHAR(255), IN `ADDRESS` TEXT, IN `SELL` VARCHAR(10), IN `MAKE` VARCHAR(10), IN `BUY` VARCHAR(10), IN `IS_DELETED` ENUM('1','0'), IN `CREATED_BY` INT(11))   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_branch b 
			SET	
				b.is_deleted = IS_DELETED
				, b.created_by = CREATED_BY
			WHERE b.branch_id = BRANCH_ID AND b.client_id = CLIENT_ID;
		
			SELECT  b.branch_id         
	        		, 'Branch deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_branch b
	    	WHERE 	b.branch_id = CLIENT_ID;
		END;
	ELSEIF(BRANCH_ID <> 0)THEN
		BEGIN
			UPDATE tbl_branch b 
			SET		
				b.branch_name = BRANCH_NAME
				, b.legal_name = LEGAL_NAME
				, b.address = ADDRESS
				, b.created_by = CREATED_BY
			WHERE b.branch_id = BRANCH_ID;
		
			SELECT  b.branch_id
	        		, 'Branch detail updated successfully!' AS message
	        		, 1 action
		    FROM  	tbl_branch b
	    	WHERE 	b.branch_id = BRANCH_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_branch l WHERE l.branch_name = BRANCH_NAME AND l.is_deleted = '0' AND l.client_id = CLIENT_ID)
	THEN
		BEGIN 
			SELECT 	l.branch_id
					, l.branch_name
					, l.legal_name
					, l.address
					, l.sell
					, l.make
					, l.buy
					, 'Branch name already exist' AS message
					, 0 action
			FROM tbl_branch l
			WHERE l.branch_name = BRANCH_NAME AND l.client_id = CLIENT_ID;
		END;
	ELSE
		BEGIN	
			INSERT INTO `tbl_branch`(client_id, branch_name, legal_name, address, sell, make, buy)
			VALUES(CLIENT_ID, BRANCH_NAME, LEGAL_NAME, ADDRESS, SELL, MAKE, BUY);
		
			SELECT 	l.branch_id
					, l.branch_name
					, l.legal_name
					, l.address
					, l.sell
					, l.make
					, l.buy
					, 'Branch name save successfully' AS message
					, l.status
					, l.created_by 
					, 1 action
			FROM tbl_branch l
			WHERE l.branch_id = LAST_INSERT_ID();
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_CATEGORY` (IN `CATEGORY_ID` BIGINT(255), IN `CATEGORY_NAME` VARCHAR(255), IN `SLUG` VARCHAR(255), IN `HSN_CODE` VARCHAR(255), IN `IS_DELETED` ENUM('1','0'), IN `CREATED_BY` INT)   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_category c 
			SET	
				c.is_deleted = IS_DELETED
				, c.created_by = CREATED_BY
			WHERE c.category_id = CATEGORY_ID;
		
			SELECT  c.category_id         
	        		, 'Category deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_category c
	    	WHERE 	c.category_id = CATEGORY_ID;
		END;
	ELSEIF(CATEGORY_ID <> 0)THEN
		BEGIN
			UPDATE tbl_category c 
			SET		
				c.name = CATEGORY_NAME
				, c.hsn_code = SLUG
				, c.created_by = CREATED_BY
			WHERE c.category_id = CATEGORY_ID;
		
			SELECT  c.category_id         
	        		, 'Category deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_category c
	    	WHERE 	c.category_id = CATEGORY_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_category c WHERE c.name = CATEGORY_NAME OR c.hsn_code = HSN_CODE AND c.is_deleted='0') THEN 
		BEGIN 
			SELECT 	c.category_id
					, c.name 
					, c.hsn_code 
					, 'Category name or HSN code already exists' AS message
					, 0 action
			FROM 	tbl_category c
			WHERE 	c.name = CATEGORY_NAME;
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_category(name,slug,hsn_code,created_by) 
			VALUES (CATEGORY_NAME,SLUG,HSN_CODE,CREATED_BY);
			
			SELECT	c.category_id
					, c.name category_name
					, c.slug
					, 'Category name save successfully' AS message
					, 1 action
			FROM 	tbl_category c
			WHERE 	c.category_id = LAST_INSERT_ID(); 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_CUSTOMER` (IN `CUSTOMER_ID` BIGINT, IN `FIRST_NAME` VARCHAR(255), IN `LAST_NAME` VARCHAR(255), IN `COMPANY_NAME` VARCHAR(255), IN `DISPLAY_NAME` VARCHAR(255), IN `EMAIL` VARCHAR(255), IN `BILLING_ADDRESS` TEXT, IN `SHIPPING_ADDRESS` TEXT, IN `MOBILE_NO` BIGINT(20), IN `PAN_NO` VARCHAR(255), IN `GST_NO` VARCHAR(255), IN `COMMENTS` TEXT, IN `CREATED_BY` INT(10))   BEGIN
  IF(CUSTOMER_ID<>0) THEN 
    BEGIN
      UPDATE tbl_customer c
      SET
        c.first_name=FIRST_NAME 
        ,c.last_name=LAST_NAME 
        ,c.company_name=COMPANY_NAME
        ,c.display_name=DISPLAY_NAME
        ,c.email=EMAIL
        ,c.mobile_no=MOBILE_NO
        ,c.pan_no=PAN_NO
        ,c.gst_no=GST_NO
        ,c.billing_address=BILLING_ADDRESS
        ,c.shipping_address=SHIPPING_ADDRESS
        ,c.additional_comments=COMMENTS
        ,c.created_by=CREATED_BY
      WHERE c.customer_id=CUSTOMER_ID;
      SELECT CUSTOMER_ID AS customer_id, 1 AS action, "Data updated successfully" as message;
    END; 
  ELSE
    BEGIN
      INSERT INTO tbl_customer(first_name, last_name, company_name, display_name, email, billing_address, shipping_address, mobile_no, pan_no, gst_no, additional_comments, created_by) 
      VALUES (FIRST_NAME, LAST_NAME, COMPANY_NAME, DISPLAY_NAME, EMAIL, BILLING_ADDRESS, SHIPPING_ADDRESS, MOBILE_NO, PAN_NO, GST_NO, COMMENTS, CREATED_BY);
      
      SELECT LAST_INSERT_ID() AS customer_id, 1 AS action, "Data save successfully" as message;      
    END;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_DEFAULT_TAX` (IN `SALE_ORDER` BIGINT, IN `PURCHASE_ORDER` BIGINT, IN `CREATED_BY` INT)   BEGIN
	UPDATE tbl_default_tax_rate d SET d.is_deleted = '1' WHERE d.is_deleted = '0';
	
	INSERT INTO tbl_default_tax_rate(sales_order_tax, purchase_order_tax,created_by) 
	VALUES (SALE_ORDER, PURCHASE_ORDER, CREATED_BY);
	
	SELECT	
			t.default_tax_id
			, t.sales_order_tax
			, t.purchase_order_tax
			, t.status
			, t.created_by
			, t.created_date			
			, 'Default rate updated successfully' AS message 
			, 1 action
	FROM 	tbl_default_tax_rate t
	WHERE 	t.default_tax_id = LAST_INSERT_ID(); 

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_DEPARTMENT` (IN `DEPARTMENT_NAME` VARCHAR(255), IN `CREATED_BY` INT, IN `IS_DELETED` ENUM('1','0'), IN `DEPARTMENT_ID` BIGINT)   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_department d
			SET	
				d.is_deleted = IS_DELETED
				, d.created_by = CREATED_BY
			WHERE d.department_id = DEPARTMENT_ID;
		
			SELECT  d.department_id         
	        		, 'Department deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_department d
	    	WHERE 	d.department_id = DEPARTMENT_ID;
		END;
	ELSEIF(DEPARTMENT_ID <> 0)THEN
		BEGIN
			UPDATE tbl_department d 
			SET		
				d.department_name = DEPARTMENT_NAME
				, d.created_by = CREATED_BY
			WHERE d.department_id = DEPARTMENT_ID;
		
			SELECT  d.department_id         
	        		, 'Department updated successfully!' AS message
	        		, 1 action
		    FROM  	tbl_department d
	    	WHERE 	d.department_id = DEPARTMENT_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_department d WHERE d.department_name = DEPARTMENT_NAME AND d.is_deleted = '0') THEN 
		BEGIN 
			SELECT	d.department_id
					, d.department_name
					, 'Department detail already exists!' AS message
					, 0 action
			FROM 	tbl_department d
			WHERE 	d.department_name = DEPARTMENT_NAME;
		END;	
	ELSE
		BEGIN
INSERT INTO tbl_department(department_name,created_by,department_id) 
			VALUES (DEPARTMENT_NAME,CREATED_BY,DEPARTMENT_ID);
			
			SELECT	d.department_id
					, d.department_name
					, 'Department name save successfully' AS message
					, 1 action
			FROM 	tbl_department d
			WHERE 	d.department_id = LAST_INSERT_ID(); 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_EMPLOYEE` (IN `DEPARTMENT_ID` BIGINT, IN `STORE_ID` BIGINT, IN `EMPLOYEE_NAME` VARCHAR(255), IN `EMPLOYEE_EMAIL` VARCHAR(255), IN `EMPLOYEE_MOBILE_NO` BIGINT, IN `EMPLOYEE_GENDER` VARCHAR(255), IN `EMPLOYEE_ADDRESS` TEXT, IN `EMPLOYEE_DESIGNATION` VARCHAR(255), IN `EMPLOYEE_DOB` DATE, IN `CREATED_BY` BIGINT)   BEGIN
	IF EXISTS(SELECT 1 FROM tbl_employee e WHERE e.employee_name = EMPLOYEE_NAME AND e.employee_mobile_no = EMPLOYEE_MOBILE_NO) 
	THEN 
		BEGIN 
			SELECT 	e.employee_id
					, e.employee_name
					, 'Employee already exists' AS message
					, 0 action
			FROM 	tbl_employee e
			WHERE e.employee_name = EMPLOYEE_NAME AND e.employee_mobile_no = EMPLOYEE_MOBILE_NO;
		END;
	ELSE
		BEGIN
			INSERT INTO tbl_employee(department_id, store_id, employee_name, employee_email, employee_mobile_no, employee_gender, employee_address, employee_designation, employee_dob,created_by)			
			VALUES (DEPARTMENT_ID, STORE_ID, EMPLOYEE_NAME, EMPLOYEE_EMAIL, EMPLOYEE_MOBILE_NO, EMPLOYEE_GENDER, EMPLOYEE_ADDRESS, EMPLOYEE_DESIGNATION, EMPLOYEE_DOB, CREATED_BY);
			
			SELECT 	e.employee_id
					, e.employee_name
					, 'Employee detail saved successfully' AS message
					, 1 action
			FROM 	tbl_employee e
			WHERE 	e.employee_id = LAST_INSERT_ID(); 		
		END;
	END IF;
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_FG` (IN `FG_ID` BIGINT(20), IN `FG_CODE` VARCHAR(255), IN `SALES_QTY` VARCHAR(255), IN `FG_DISCRIPTION` TEXT, IN `CREATED_BY` INT(20), IN `IS_DELETED` ENUM('0','1'), IN `STORE_ID` BIGINT(20))   BEGIN
    IF(IS_DELETED <> '0')THEN
    BEGIN
        UPDATE tbl_fg f
        SET    f.is_deleted = IS_DELETED
        WHERE f.fg_id = FG_ID;
    
        SELECT  f.fg_id       
                , 'FG deleted successfully' AS message
                , 1 action
        FROM    tbl_fg f
        WHERE   f.fg_id = FG_ID;
    END;    
    ELSEIF(FG_ID <> 0)THEN
    BEGIN
        UPDATE tbl_fg f
        SET    
            f.fg_code = FG_CODE,
            f.fg_discription = FG_DISCRIPTION,
            f.sales_qty = SALES_QTY  -- Removed the extra comma here
        WHERE f.fg_id = FG_ID;
        
        SELECT  f.fg_id     
                , 'FG detail updated successfully' AS message
                , 1 action
        FROM    tbl_fg f
        WHERE   f.fg_id = FG_ID;
    END;
    ELSEIF EXISTS(SELECT 1 FROM tbl_fg f WHERE f.fg_code = FG_CODE) THEN 
    BEGIN 
        SELECT  f.fg_id         
                , 'FG code already exists' AS message
                , 0 action
        FROM    tbl_fg f
        WHERE   f.fg_code = FG_CODE;
    END;
    ELSE
    BEGIN
        INSERT INTO tbl_fg(fg_code, fg_discription, sales_qty, created_by, created_date, store_id) 
        VALUES (FG_CODE, FG_DISCRIPTION, SALES_QTY, CREATED_BY, CREATED_DATE, STORE_ID);
    
        SELECT LAST_INSERT_ID() AS raw_material_id, 1 AS action, "Raw material detail saved successfully" as message;      
    END;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_GENERAL_ISSUES` (IN `STORE_ID` BIGINT, IN `PURCHASE_ORDER_ID` VARCHAR(255), IN `GENERAL_ISSUES_NO` VARCHAR(255), IN `DEPARTMENT_ID` BIGINT, IN `EMPLOYEE_ID` BIGINT, IN `IS_DELETED` ENUM('1','0'), IN `CREATED_BY` BIGINT, IN `FG_ID` BIGINT(20), IN `FG_QUANTITY` BIGINT(20), IN `RM_COUNT_ID` BIGINT(20))   BEGIN
	IF EXISTS(SELECT 1 FROM tbl_general_issues g WHERE g.general_issues_no = GENERAL_ISSUES_NO AND g.store_id = STORE_ID ) THEN 
  		BEGIN 
	    	SELECT  g.general_issues_id
	        		, 'General Issues no. already exists' AS message
	        		, 0 action
		    FROM  	tbl_general_issues g
	    	WHERE g.general_issues_no = GENERAL_ISSUES_NO AND g.store_id = STORE_ID;
		END;
  	ELSE
		BEGIN
			INSERT INTO tbl_general_issues
				(store_id, purchase_order_id, general_issues_no, department_id, employee_id, is_deleted, created_by, fg_id, fg_quantity, no_of_rm)
			VALUES 
				(STORE_ID, PURCHASE_ORDER_ID, GENERAL_ISSUES_NO, DEPARTMENT_ID, EMPLOYEE_ID, IS_DELETED, CREATED_BY, FG_ID, FG_QUANTITY, RM_COUNT_ID);

			SELECT 
					LAST_INSERT_ID() AS general_issues_id
					, 1 AS action, "General Issues item saved successfully." as message;
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_GRN_DETAIL` (IN `PRODUCT_GRN_ID` VARCHAR(255), IN `SUPPLIER_ID` BIGINT, IN `PO_DATE` DATE, IN `CATEGORY_ID` BIGINT, IN `INVOICE_TYPE` VARCHAR(255), IN `BILL_NO` VARCHAR(255), IN `PRODUCT_ID` BIGINT, IN `MFG_DATE` VARCHAR(255), IN `EXPIRY_DATE` VARCHAR(255), IN `NO_OF_BOXES` BIGINT, IN `NO_OF_ITEMS` BIGINT, IN `CREATED_BY` BIGINT, IN `PART_CODE` VARCHAR(255), IN `SERIAL_NO` VARCHAR(255), IN `LOT_NO` VARCHAR(255), IN `PACKING` BIGINT(20))   BEGIN
	INSERT INTO tbl_product_grn_detail
	(product_grn_id, category_id, bill_type, bill_no, po_date, supplier_id, product_id, mfg_date, expiry_date, no_of_boxes, no_of_items, created_by, part_code, serial_no, lot_no, packing) 
	VALUES 
	(PRODUCT_GRN_ID, CATEGORY_ID, INVOICE_TYPE, BILL_NO, PO_DATE, SUPPLIER_ID, PRODUCT_ID, MFG_DATE, EXPIRY_DATE, NO_OF_BOXES, NO_OF_ITEMS, CREATED_BY, PART_CODE, SERIAL_NO, LOT_NO, PACKING);

	SELECT 	LAST_INSERT_ID() AS product_grn_detail_id
			, 1 AS action, "GRN no. saved successfully." as message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_GRN_TYPE` (IN `GRN_TYPE_ID` BIGINT(20), IN `GRN_TYPE_NAME` VARCHAR(255), IN `IS_DELETED` ENUM('1','0'), IN `CLIENT_ID` BIGINT(20))   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_grn_type gt 
			SET	
				gt.is_deleted = IS_DELETED
			WHERE gt.grn_type_id = GRN_TYPE_ID;
		
			SELECT  gt.grn_type_id        
	        		, 'Grn Type deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_grn_type gt
	    	WHERE 	gt.grn_type_id = GRN_TYPE_ID;
		END;
	ELSEIF(GRN_TYPE_ID <> 0)THEN
		BEGIN
			UPDATE tbl_grn_type gt
			SET		
				gt.grn_type_name = GRN_TYPE_NAME
			WHERE gt.grn_type_id = GRN_TYPE_ID;
            
			SELECT  gt.grn_type_id         
	        		, 'Grn Type name updated successfully!' AS message
	        		, 1 action
		    FROM  	tbl_grn_type gt
	    	WHERE 	gt.grn_type_id = GRN_TYPE_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_grn_type gt WHERE gt.grn_type_name = GRN_TYPE_NAME AND gt.is_deleted = '0') THEN 
		BEGIN 
			SELECT	gt.grn_type_id
					, gt.grn_type_name
					, 'Grn type detail already exists!' AS message
					, 0 action
			FROM 	tbl_grn_type gt
			WHERE 	gt.grn_type_name = GRN_TYPE_NAME;
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_grn_type(grn_type_name, is_deleted, client_id)
			VALUES (GRN_TYPE_NAME, IS_DELETED, CLIENT_ID);
			
			SELECT 	LAST_INSERT_ID() grn_type_id
			, 1 AS action, "GRN Type Name saved successfully." as message; 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_INVOICE` (IN `INVOICE_ID` BIGINT(20), IN `INVOICE_NAME` VARCHAR(255), IN `IS_DELETED` ENUM('1','0'), IN `CLIENT_ID` BIGINT(20))   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_invoice i
			SET	
				i.is_deleted = IS_DELETED
			WHERE i.invoice_id = INVOICE_ID;
		
			SELECT  i.invoice_id        
	        		, 'Invoice deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_invoice i
	    	WHERE 	i.invoice_id = INVOICE_ID;
		END;
	ELSEIF(INVOICE_ID <> 0)THEN
		BEGIN
			UPDATE  tbl_invoice i
			SET		
				i.invoice_name = INVOICE_NAME
			WHERE i.invoice_id = INVOICE_ID;
		
			SELECT  i.invoice_id      
	        		, 'Invoice updated successfully!' AS message
	        		, 1 action
		    FROM  	tbl_invoice i
	    	WHERE 	i.invoice_id = INVOICE_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_invoice i WHERE i.invoice_name = INVOICE_NAME AND i.is_deleted = '0') THEN 
		BEGIN 
			SELECT	i.invoice_id
					, i.invoice_name
					, 'Invoice already exists!' AS message
					, 0 action
			FROM 	tbl_invoice i
			WHERE 	i.invoice_name = INVOICE_NAME;
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_invoice(invoice_id, invoice_name, is_deleted, client_id)
			VALUES (INVOICE_ID, INVOICE_NAME, IS_DELETED, CLIENT_ID);
			
			SELECT 	LAST_INSERT_ID() invoice_id
			, 1 AS action, "Invoice Name saved successfully." as message; 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_LOCATIONS` (IN `STORE_ID` BIGINT, IN `FLOOR_NO` VARCHAR(255), IN `ROOM_NO` VARCHAR(255), IN `RACK_NO` VARCHAR(255), IN `SHELF_NO` VARCHAR(255), IN `BIN_NO` VARCHAR(255), IN `LOCATION_NAME` VARCHAR(255), IN `LOCATION_REMARKS` TEXT, IN `CREATED_BY` INT(11), IN `IS_DELETED` ENUM('1','0'), IN `LOCATION_ID` BIGINT)   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_locations l
			SET	
				l.is_deleted = IS_DELETED
				, l.created_by = CREATED_BY
			WHERE l.location_id = LOCATION_ID;
		
			SELECT  l.location_id         
	        		, 'Location deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_locations l
	    	WHERE 	l.location_id = LOCATION_ID;
		END;
	ELSEIF(LOCATION_ID <> 0)THEN
		BEGIN
			UPDATE tbl_locations l 
			SET		
				l.location_name = LOCATION_NAME
				, l.created_by = CREATED_BY
			WHERE l.location_id = LOCATION_ID;
		
			SELECT  l.location_id        
	        		, 'Location updated successfully!' AS message
	        		, 1 action
		    FROM  	tbl_locations l
	    	WHERE 	l.location_id = LOCATION_ID;
		END;
	ELSEIF EXISTS(
				SELECT 1
				FROM tbl_locations l
				WHERE   l.store_id = STORE_ID
						AND l.floor_no = FLOOR_NO
						AND l.room_no = ROOM_NO
						AND l.rack_no = RACK_NO
						AND l.shelf_no = SHELF_NO
						AND l.bin_no = BIN_NO
						AND l.is_deleted = '0'
			  	)
	THEN
		BEGIN 
			SELECT 
					  l.location_id
					, l.store_id
					, l.floor_no
					, l.room_no
					, l.rack_no
					, l.shelf_no
					, l.bin_no
					, l.location_name
					, l.location_remarks
					, l.status
                    , l.is_deleted
					, l.created_by
					, l.created_date 
					, 'Location already exist' AS message
					, 0 action
			FROM tbl_locations l
			WHERE   store_id = STORE_ID
					AND floor_no = FLOOR_NO
					AND room_no = ROOM_NO
					AND rack_no = RACK_NO
					AND shelf_no = SHELF_NO
					AND bin_no = BIN_NO;
		END;
	ELSE
		BEGIN	
			INSERT INTO `tbl_locations`(store_id, floor_no, room_no, rack_no, shelf_no, bin_no, location_name, location_remarks) 
			VALUES(STORE_ID, FLOOR_NO, ROOM_NO, RACK_NO, SHELF_NO, BIN_NO, LOCATION_NAME, LOCATION_REMARKS);
		
			UPDATE tbl_locations l SET l.location_no = LAST_INSERT_ID()+10000 WHERE l.location_id = LAST_INSERT_ID(); 
		
			SELECT 	 l.location_id
					, l.store_id
					, l.floor_no
					, l.room_no
					, l.rack_no
					, l.shelf_no
					, l.bin_no
					, l.location_name
					, l.location_remarks					
					, 'Location detail save successfully' AS message
					, l.status
					, l.created_by 
					, 1 action
			FROM tbl_locations l
			WHERE l.location_id = LAST_INSERT_ID();
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_MODEL` (IN `MODEL_ID` BIGINT, IN `STORE_ID` BIGINT, IN `CATEGORY_ID` BIGINT, IN `MODEL_NAME` VARCHAR(255), IN `IS_DELETED` ENUM('1','0'), IN `CREATED_BY` BIGINT)   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_model m 
			SET	
				m.is_deleted = IS_DELETED
				, m.created_by = CREATED_BY
			WHERE m.model_id = MODEL_ID;
		
			SELECT  m.model_id         
	        		, 'Model deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_model m
	    	WHERE 	m.model_id = MODEL_ID;
		END;
	ELSEIF(MODEL_ID <> 0)THEN
		BEGIN
			UPDATE tbl_model m 
			SET		
				m.model = MODEL_NAME
				, m.category_id = CATEGORY_ID
				, m.created_by = CREATED_BY
			WHERE m.model_id = MODEL_ID;
		
			SELECT  m.model_id         
	        		, 'Model updated successfully!' AS message
	        		, 1 action
		    FROM  	tbl_model m
	    	WHERE 	m.model_id = MODEL_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_model m WHERE m.model = MODEL_NAME AND m.category_id = CATEGORY_ID AND m.is_deleted = '0') THEN 
		BEGIN 
			SELECT	m.model_id
					, m.model
					, 'Model detail already exists!' AS message
					, 0 action
			FROM 	tbl_model m
			WHERE 	m.model = MODEL_NAME;
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_model(store_id, category_id, model, created_by)
			VALUES (STORE_ID, CATEGORY_ID, MODEL_NAME, CREATED_BY);
			
			SELECT	m.model_id
					, m.model
					, 'Model detail saved successfully!' AS message
					, 1 action
			FROM 	tbl_model m
			WHERE 	m.model_id = LAST_INSERT_ID(); 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_OPERATION` (IN `OPERATION_NAME` VARCHAR(255), IN `CREATED_BY` INT)   BEGIN
	IF EXISTS(SELECT 1 FROM tbl_operations o WHERE o.operation_title = OPERATION_NAME AND o.is_deleted = '1') THEN 
		BEGIN 
			SELECT 	o.operation_id
					, o.operation_title 
					, 'Operation title already exists' AS message
					, 0 action
			FROM 	tbl_operations o
			WHERE 	o.operation_title = OPERATION_NAME;
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_operations(operation_title, created_by) 
			VALUES (OPERATION_NAME, CREATED_BY);
			
			SELECT	o.operation_id
					, o.operation_title
					, o.created_by 
					, o.status 
					, 'Operation title save successfully' AS message 
					, 1 action
			FROM 	tbl_operations o
			WHERE 	o.operation_id = LAST_INSERT_ID(); 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_PACKAGE_TYPE` (IN `PACKAGE_TYPE` VARCHAR(255), IN `CREATED_BY` INT)   BEGIN
	IF EXISTS(SELECT 1 FROM tbl_package_type P WHERE P.package_type = PACKAGE_TYPE) THEN 
		BEGIN 
			SELECT 	P.package_type_id
					, P.package_type 
					, 'Package type already exists' AS message
					, 0 action
			FROM 	tbl_package_type P
			WHERE 	P.package_type = PACKAGE_TYPE;
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_package_type(package_type,created_by) 
			VALUES (PACKAGE_TYPE,CREATED_BY);
			
			SELECT	P.package_type_id
					, P.package_type					
					, 'Package type save successfully' AS message
					, 1 action
			FROM 	tbl_package_type P
			WHERE 	P.package_type_id = LAST_INSERT_ID(); 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_PRODUCT` (IN `PRODUCT_ID` BIGINT, IN `CATEGORY_ID` BIGINT, IN `OEM_ID` BIGINT, IN `MODEL` VARCHAR(255), IN `QUALITY` VARCHAR(255), IN `SIZE` VARCHAR(255), IN `SKU` VARCHAR(255), IN `HSN_CODE` VARCHAR(255), IN `ARTICLE_CODE` VARCHAR(255), IN `UNIT_ID` BIGINT, IN `PRODUCT_NAME` VARCHAR(255), IN `ADDITIONAL_INFO` TEXT, IN `IS_DELETED` ENUM('1','0'), IN `CREATED_BY` BIGINT, IN `CREATED_DATE` TIMESTAMP)   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_product p 
			SET	p.is_deleted = IS_DELETED
			WHERE p.product_id = PRODUCT_ID;
		
			SELECT  p.product_id         
	        		, 'Product deleted successfully' AS message
	        		, 1 action
		    FROM  	tbl_product p
	    	WHERE 	p.product_id = PRODUCT_ID;
		END;	
	ELSEIF(PRODUCT_ID <> 0)THEN
		BEGIN
			UPDATE tbl_product p 
			SET	
				p.category_id = CATEGORY_ID	
				, p.oem_id = OEM_ID
				, p.model = MODEL
				, p.quality = QUALITY
				, p.size = SIZE
				, p.sku = SKU
				, p.hsn_code = HSN_CODE
				, p.article_code = ARTICLE_CODE
				, p.unit_id = UNIT_ID				
				, product_name = PRODUCT_NAME
				, p.additional_info = ADDITIONAL_INFO
				, p.created_by = CREATED_BY
			WHERE p.product_id = PRODUCT_ID;
			SELECT  p.product_id         
	        		, 'Product detail updated successfully' AS message
	        		, 1 action
		    FROM  	tbl_product p
	    	WHERE 	p.product_id = PRODUCT_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_product p WHERE p.product_name = PRODUCT_NAME) THEN 
  		BEGIN 
	    	SELECT  p.product_id         
	        		, 'Product name already exists' AS message
	        		, 0 action
		    FROM  	tbl_product p
	    	WHERE 	p.product_name = PRODUCT_NAME;
		END;
  	ELSE
    	BEGIN
      		INSERT INTO tbl_product(product_name, oem_id, category_id, size, model, quality, sku, hsn_code, article_code, additional_info, unit_id, created_by, created_date) 
      		VALUES (PRODUCT_NAME, OEM_ID, CATEGORY_ID, SIZE, MODEL, QUALITY, SKU, HSN_CODE, ARTICLE_CODE, ADDITIONAL_INFO, UNIT_ID, CREATED_BY, CREATED_DATE);
      
      		SELECT LAST_INSERT_ID() AS product_id,1 AS action, "Product detail saved successfully" as message;      
	    END;
  	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_PRODUCT_ALIAS` (IN `PRODUCT_ALIAS_ID` BIGINT, IN `STORE_ID` BIGINT, IN `PRODUCT_ID` BIGINT, IN `PRODUCT_ALIAS_NAME` VARCHAR(255), IN `IS_DELETED` ENUM('1','0'), IN `CREATED_BY` BIGINT, IN `FG_ID` BIGINT(20), IN `FG_PROD_NAME` VARCHAR(255), IN `SUPPLIER_ID` BIGINT(20))   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_product_alias s 
			SET	
				s.is_deleted = IS_DELETED
				, s.created_by = CREATED_BY
			WHERE s.product_alias_id = PRODUCT_ALIAS_ID;
		
			SELECT  s.product_alias_id         
	        		, 'Product alias detail deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_product_alias s
	    	WHERE 	s.product_alias_id = PRODUCT_ALIAS_ID;
		END;
	ELSEIF(PRODUCT_ALIAS_ID <> 0)THEN
		BEGIN
			UPDATE tbl_product_alias s 
			SET		
				s.product_alias_name = PRODUCT_ALIAS_NAME
				, s.product_id = PRODUCT_ID
				, s.fg_id = FG_ID
				, s.fg_prod_name = FG_PROD_NAME
				, s.created_by = CREATED_BY
			WHERE s.product_alias_id = PRODUCT_ALIAS_ID;
		
			SELECT  s.product_alias_id         
	        		, 'Product alias detail deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_product_alias s
	    	WHERE 	s.product_alias_id = PRODUCT_ALIAS_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_product_alias s WHERE s.product_alias_name = PRODUCT_ALIAS_NAME AND s.is_deleted = '0') THEN 
		BEGIN 
			SELECT	s.product_alias_id
					, s.product_alias_name
					, 'Product alias detail already exists!' AS message
					, 0 action
			FROM 	tbl_product_alias s
			WHERE 	s.product_alias_name = PRODUCT_ALIAS_NAME;
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_product_alias(store_id, product_id, product_alias_name, created_by, fg_id,fg_prod_name, supplier_id)
			VALUES (STORE_ID, PRODUCT_ID, PRODUCT_ALIAS_NAME, CREATED_BY, FG_ID,FG_PROD_NAME, SUPPLIER_ID);
			
			SELECT	s.product_alias_id
					, s.product_alias_name
					, 'Product alias detail saved successfully!' AS message
					, 1 action
			FROM 	tbl_product_alias s
			WHERE 	s.product_alias_id = LAST_INSERT_ID(); 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_PRODUCT_GRN` (IN `STORE_ID` BIGINT, IN `PRODUCT_GRN_ID` BIGINT, IN `PRODUCT_GRN_NO` VARCHAR(255), IN `QUALITY_CHECK` ENUM('0','1'), IN `ADDITIONAL_INFO` TEXT, IN `CREATED_BY` BIGINT, IN `GRN_TYPE_ID` BIGINT)   BEGIN
	IF EXISTS(SELECT 1 FROM tbl_product_grn p WHERE p.product_grn_no = PRODUCT_GRN_NO AND p.store_id = STORE_ID) THEN 
  		BEGIN 
	    	SELECT  p.product_grn_id         
	        		, 'GRN no. already exists' AS message
	        		, 0 action
		    FROM  	tbl_product_grn p
	    	WHERE 	p.product_grn_no = PRODUCT_GRN_NO AND p.store_id = STORE_ID;
		END;
	ELSEIF(QUALITY_CHECK <> 0 && PRODUCT_GRN_ID <> 0)THEN
		BEGIN
			UPDATE tbl_product_grn tpg 
			SET	tpg.is_quality_checked = QUALITY_CHECK
			WHERE tpg.product_grn_id = PRODUCT_GRN_ID;
		
			SELECT PRODUCT_GRN_ID, 1 AS action;
	
		END;	
  	ELSE
		BEGIN
			INSERT INTO `tbl_product_grn`(store_id, grn_type_id, product_grn_no,additional_info, created_by) 
			VALUES (STORE_ID, GRN_TYPE_ID, PRODUCT_GRN_NO, ADDITIONAL_INFO, CREATED_BY);
		
			SELECT 
					LAST_INSERT_ID() AS product_grn_id
					, 1 AS action, "GRN no. saved successfully." as message;
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_PRODUCT_QUALITY_CHECK` (IN `GRN_DETAIL_ID` BIGINT, IN `NO_OF_BOXES` BIGINT, IN `QUALITY_CHECKED_ITEM` BIGINT, IN `PURCHASE_PRICE_PER_ITEM` FLOAT(10,2), IN `CREATED_BY` BIGINT)   BEGIN
	UPDATE tbl_product_grn_detail tpgd
	SET 
	no_of_boxes = NO_OF_BOXES
	, quality_checked_item = QUALITY_CHECKED_ITEM
	, purchase_price_per_item = PURCHASE_PRICE_PER_ITEM
	WHERE product_grn_detail_id = GRN_DETAIL_ID;

	SELECT  'Quality checked done successfully.' AS message
	        , 1 action;		    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_PURCHASE_ORDER` (IN `PURCHASE_ORDER_ID` BIGINT, IN `STORE_ID` BIGINT, IN `PURCHASE_ORDER_NO` VARCHAR(255), IN `PO_DATE` DATE, IN `CUSTOMER_ID` BIGINT, IN `BILLING_ADDRESS` TEXT, IN `SHIPPING_ADDRESS` TEXT, IN `SHIPPING_DESCRIPTION` TEXT, IN `SHIPPING_COST` FLOAT(10,2), IN `TAX_ID` INT, IN `ADDITIONAL_INFO` TEXT, IN `IS_DELETED` ENUM('1','0'), IN `CREATED_BY` BIGINT)   BEGIN
	IF EXISTS(SELECT 1 FROM tbl_purchase_order p WHERE p.purchase_order_no = PURCHASE_ORDER_NO AND p.store_id = STORE_ID) THEN 
  		BEGIN 
	    	SELECT  p.purchase_order_id
	        		, 'Purchase order no. already exists' AS message
	        		, 0 action
		    FROM  	tbl_purchase_order p
	    	WHERE 	p.purchase_order_no = PURCHASE_ORDER_NO AND p.store_id = STORE_ID;
		END; 
  	ELSE
		BEGIN
			INSERT INTO tbl_purchase_order
			(store_id, purchase_order_no, po_date, customer_id, billing_address, shipping_address, shipping_description, shipping_cost, tax_id, additional_info, created_by)
			VALUES
			(STORE_ID, PURCHASE_ORDER_NO, PO_DATE, CUSTOMER_ID, BILLING_ADDRESS, SHIPPING_ADDRESS, SHIPPING_DESCRIPTION, SHIPPING_COST, TAX_ID, ADDITIONAL_INFO, CREATED_BY);
		
			SELECT 
					LAST_INSERT_ID() AS purchase_order_id
					, 1 AS action, "Purchase order saved successfully." as message;
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_PURCHASE_ORDER_DETAIL` (IN `PURCHASE_ORDER_ID` BIGINT, IN `PRODUCT_ID` BIGINT, IN `PRODUCT_ALIAS` VARCHAR(255), IN `QUANTITY` BIGINT, IN `PRICE_PER_UNIT` FLOAT(10,2), IN `DISCOUNT_PERCENTAGE` INT, IN `TAX_ID` INT, IN `CREATED_BY` BIGINT)   BEGIN	
	INSERT INTO tbl_purchase_order_detail(purchase_order_id, product_id, product_alias, quantity, price_per_unit, discount_percentage, tax_id,created_by) 
	VALUES(PURCHASE_ORDER_ID, PRODUCT_ID, PRODUCT_ALIAS, QUANTITY, PRICE_PER_UNIT, DISCOUNT_PERCENTAGE, TAX_ID, CREATED_BY);
	
	SELECT 	LAST_INSERT_ID() AS purchase_order_detail_id
			, 1 AS action, "Sales order detail saved successfully." as message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_QUALITY` (IN `QUALITY_ID` BIGINT, IN `STORE_ID` BIGINT, IN `CATEGORY_ID` BIGINT, IN `QUALITY_NAME` VARCHAR(255), IN `IS_DELETED` ENUM('1','0'), IN `CREATED_BY` BIGINT)   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_quality q 
			SET	
				q.is_deleted = IS_DELETED
				, q.created_by = CREATED_BY
			WHERE q.quality_id = QUALITY_ID;
		
			SELECT  q.quality_id         
	        		, 'Quality deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_quality q
	    	WHERE 	q.quality_id = QUALITY_ID;
		END;
	ELSEIF(QUALITY_ID <> 0)THEN
		BEGIN
			UPDATE tbl_quality q 
			SET		
				q.quality = QUALITY_NAME
				, q.category_id = CATEGORY_ID
				, q.created_by = CREATED_BY
			WHERE q.quality_id = QUALITY_ID;
		
			SELECT  q.quality_id         
	        		, 'Quality updated successfully!' AS message
	        		, 1 action
		    FROM  	tbl_quality q
	    	WHERE 	q.quality_id = QUALITY_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_quality q WHERE q.quality = QUALITY_NAME AND q.is_deleted = '0') THEN 
		BEGIN 
			SELECT	q.quality_id
					, q.quality
					, 'Quality detail already exists!' AS message
					, 0 action
			FROM 	tbl_quality q
			WHERE 	q.quality = QUALITY_NAME;
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_quality(store_id, category_id, quality, created_by)
			VALUES (STORE_ID, CATEGORY_ID, QUALITY_NAME, CREATED_BY);
			
			SELECT	q.quality_id
					, q.quality
					, 'Quality detail saved successfully!' AS message
					, 1 action
			FROM 	tbl_quality q
			WHERE 	q.quality_id = LAST_INSERT_ID(); 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_RAW_MATERIAL` (IN `RAW_MATERIAL_ID` BIGINT(20), IN `RAW_MATERIAL_NAME` VARCHAR(255), IN `RAW_MATERIAL_CODE` VARCHAR(255), IN `CATEGORY_ID` BIGINT(20), IN `SUBCATEGORY_ID` BIGINT(20), IN `SIZE` VARCHAR(255), IN `HSN_CODE` VARCHAR(50), IN `MIN_LEVEL` BIGINT(20), IN `MAX_LEVEL` BIGINT(20), IN `INWARD_UNIT_ID` BIGINT(20), IN `OUTWARD_UNIT_ID` BIGINT(20), IN `WASTAGE` BIGINT(20), IN `ADDITIONAL_INFO` TEXT, IN `IS_DELETED` ENUM('0','1'), IN `CREATED_BY` BIGINT(20), IN `CREATED_DATE` TIMESTAMP, IN `STORE_ID` BIGINT, IN `WEIGHT` FLOAT(10,2), IN `SUSTAINABILITY_SCORE` VARCHAR(255), IN `UNIT_ID` BIGINT(20))   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_raw_material rm 
			SET	rm.is_deleted = IS_DELETED
			WHERE rm.raw_material_id = RAW_MATERIAL_ID;
		
			SELECT  rm.raw_material_id       
	        		, 'Raw material deleted successfully' AS message
	        		, 1 action
		    FROM  	tbl_raw_material rm
	    	WHERE 	rm.raw_material_id = RAW_MATERIAL_ID;
		END;	
	ELSEIF(RAW_MATERIAL_ID <> 0)THEN
		BEGIN
			UPDATE tbl_raw_material rm
			SET	
				rm.category_id = CATEGORY_ID	
				, rm.subcategory_id = SUBCATEGORY_ID
				, rm.size = SIZE
				, rm.hsn_code = HSN_CODE
				, rm.raw_material_code = RAW_MATERIAL_CODE				
				, rm.raw_material_name = RAW_MATERIAL_NAME
				, rm.min_level = MIN_LEVEL
				, rm.max_level = MAX_LEVEL
				, rm.inward_unit_id = INWARD_UNIT_ID
				, rm.outward_unit_id = OUTWARD_UNIT_ID
				, rm.wastage = WASTAGE
				, rm.additional_info = ADDITIONAL_INFO
				, rm.created_by = CREATED_BY
				, rm.sustainability_score = SUSTAINABILITY_SCORE
				, rm.weight = WEIGHT
                , rm.unit_id = UNIT_ID
			WHERE rm.raw_material_id = RAW_MATERIAL_ID;
			SELECT  rm.raw_material_id      
	        		, 'Raw material detail updated successfully' AS message
	        		, 1 action
		    FROM  	tbl_raw_material rm 
	    	WHERE 	rm.raw_material_id = RAW_MATERIAL_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_raw_material rm WHERE rm.raw_material_name = RAW_MATERIAL_NAME) THEN 
  		BEGIN 
	    	SELECT  rm.raw_material_id        
	        		, 'Raw material name already exists' AS message
	        		, 0 action
		    FROM  	tbl_raw_material rm 
	    	WHERE 	rm.raw_material_name = RAW_MATERIAL_NAME;
		END;
  	ELSE
    	BEGIN
      		INSERT INTO tbl_raw_material(raw_material_name, raw_material_code, category_id, subcategory_id,size, hsn_code, min_level, max_level, inward_unit_id, outward_unit_id, wastage, additional_info,created_by, created_date, store_id, sustainability_score, weight, unit_id) 
      		VALUES (RAW_MATERIAL_NAME, RAW_MATERIAL_CODE, CATEGORY_ID, SUBCATEGORY_ID , SIZE,HSN_CODE, MIN_LEVEL, MAX_LEVEL, INWARD_UNIT_ID, OUTWARD_UNIT_ID,WASTAGE, ADDITIONAL_INFO,CREATED_BY, CREATED_DATE, STORE_ID, SUSTAINABILITY_SCORE, WEIGHT, UNIT_ID);
      
      		SELECT LAST_INSERT_ID() AS raw_material_id,1 AS action, "Raw material detail saved successfully" as message;      
	    END;
  	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_RM_BOX_DETAIL` (IN `STORE_ID` BIGINT(20), IN `RM_ID` BIGINT(20), IN `RM_GRN_ID` BIGINT(20), IN `RM_GRN_DETAIL_ID` BIGINT(20), IN `BOX_NAME` VARCHAR(255), IN `NO_OF_ITEMS` BIGINT(20), IN `CREATED_BY` BIGINT(20))   BEGIN	
	INSERT INTO tbl_rm_box_detail (store_id, rm_id, rm_grn_id, rm_grn_detail_id, box_name, no_of_items, remaining_item, created_by) 
	VALUES(STORE_ID, RM_ID, RM_GRN_ID, RM_GRN_DETAIL_ID, BOX_NAME, NO_OF_ITEMS, NO_OF_ITEMS, CREATED_BY);

	UPDATE tbl_rm_box_detail b SET b.box_no = LAST_INSERT_ID()+10000 WHERE b.rm_box_detail_id = LAST_INSERT_ID();

	SELECT 	 b.rm_box_detail_id
			, b.rm_grn_id
			, b.rm_grn_detail_id
			, b.box_name
			, b.box_no
			, b.no_of_items
			, b.created_by
			, b.created_date					
			, 'RM Box detail save successfully' AS message
			, 1 action
	FROM tbl_rm_box_detail b
	WHERE b.rm_box_detail_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_RM_BOX_LOCATION` (IN `STORE_ID` BIGINT(20), IN `LOCATION_NO` BIGINT(20), IN `BOX_NO` BIGINT(20), IN `CREATED_BY` BIGINT(20))   BEGIN
	IF EXISTS(SELECT 1 FROM tbl_sfg_box_location b WHERE b.box_no = BOX_NO AND b.store_id = STORE_ID) THEN 
  		BEGIN 
	    	SELECT  b.sfg_box_location_id
	        		, 'Put away process already completed for this box!' AS message
	        		, 0 action
		    FROM  	tbl_sfg_box_location b
	    	WHERE 	b.box_no = BOX_NO AND b.store_id = STORE_ID;
		END; 
	ELSE
    	BEGIN
			INSERT INTO tbl_sfg_box_location(store_id, location_no, box_no, created_by) 
			VALUES (STORE_ID, LOCATION_NO, BOX_NO, CREATED_BY);
			
			UPDATE tbl_sfg_box_detail tbd SET tbd.is_put_away = '1' WHERE tbd.box_no = BOX_NO; 
		
			SELECT  b.sfg_box_location_id
	        		, 'Put away process completed for this box!' AS message
	        		, 1 action
		    FROM  	tbl_sfg_box_location b
	    	WHERE 	b.box_no = BOX_NO AND b.store_id = STORE_ID;
		END;
	END IF;	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_RM_GRN` (IN `STORE_ID` BIGINT(20), IN `RM_GRN_ID` BIGINT(20), IN `RM_GRN_NO` VARCHAR(225), IN `ADDITIONAL_INFO` TEXT, IN `CREATED_BY` BIGINT(20), IN `PO_NO` BIGINT(20), IN `PO_DATE` DATE, IN `SUPPLIER_ID` BIGINT(20), IN `QUALITY_CHECK` ENUM('1','0'))   BEGIN
	IF EXISTS(SELECT 1 FROM tbl_rm_grn r WHERE r.rm_grn_no = RM_GRN_NO AND r.store_id = STORE_ID) THEN 
  		BEGIN 
	    	SELECT  r.rm_grn_id         
	        		, 'GRN no. already exists' AS message
	        		, 0 action
		    FROM  	tbl_rm_grn r
	    	WHERE 	r.rm_grn_no = RM_GRN_NO AND r.store_id = STORE_ID;
		END;
	ELSEIF(QUALITY_CHECK <> 0 && RM_GRN_ID <> 0)THEN
		BEGIN
			UPDATE tbl_rm_grn r 
			SET	r.is_rm_quality_checked = QUALITY_CHECK
			WHERE r.rm_grn_id = RM_GRN_ID;
		
			SELECT RM_GRN_ID, 1 AS action;
	
		END;
  	ELSE 
		BEGIN
			INSERT INTO `tbl_rm_grn`(store_id, rm_grn_no, po_no, po_date, supplier_id, additional_info, created_by) 
			VALUES (STORE_ID, RM_GRN_NO,PO_NO ,PO_DATE ,SUPPLIER_ID, ADDITIONAL_INFO, CREATED_BY);
		
			SELECT 
					LAST_INSERT_ID() AS rm_grn_id
					, 1 AS action, "GRN no. saved successfully." as message;
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_RM_GRN_DETAIL` (IN `RM_GRN_ID` BIGINT(20), IN `HSN_CODE` VARCHAR(255), IN `QUANTITY` BIGINT(20), IN `INWARD_UNIT_ID` VARCHAR(255), IN `RATE` FLOAT(10,2), IN `AMOUNT` FLOAT(10,2), IN `TAX_ID` BIGINT(20), IN `AFTER_TAX` FLOAT(10,2), IN `NO_OF_BOXES` BIGINT(20), IN `CREATED_BY` BIGINT(20), IN `RAW_MATERIAL_ID` BIGINT(20))   BEGIN
	INSERT INTO tbl_rm_grn_detail
	(rm_grn_id, rm_id, hsn_code, quantity, inward_unit_id, rate, amount, tax_id, after_tax, no_of_boxes,created_by) 
	VALUES 
	(RM_GRN_ID, RAW_MATERIAL_ID, HSN_CODE, QUANTITY, INWARD_UNIT_ID, RATE, AMOUNT, TAX_ID, AFTER_TAX, NO_OF_BOXES, CREATED_BY);

	SELECT 	LAST_INSERT_ID() AS rm_grn_detail_id
			, 1 AS action, "GRN no. saved successfully." as message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_RM_QUALITY_CHECK` (IN `RM_QUALITY_CHECKED_BOXES` BIGINT(20), IN `RM_QUALITY_CHECKED_ITEM` BIGINT(20), IN `PURCHASE_PRICE_PER_ITEM` BIGINT(20), IN `RM_GRN_DETAIL_ID` BIGINT(20))   BEGIN
    IF RM_GRN_DETAIL_ID IS NOT NULL AND RM_GRN_DETAIL_ID > 0 THEN
        UPDATE tbl_rm_grn_detail trgd
        SET 
            rm_quality_checked_boxes = RM_QUALITY_CHECKED_BOXES,
            rm_quality_checked_item = RM_QUALITY_CHECKED_ITEM,
            purchase_price_per_item = PURCHASE_PRICE_PER_ITEM
        WHERE trgd.rm_grn_detail_id = RM_GRN_DETAIL_ID;

        SELECT 'Quality checked done successfully.' AS message, 1 AS action;
    ELSE
        SELECT 'Invalid rm_grn_detail_id' AS message, 0 AS action;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_SALES_ORDER` (IN `STORE_ID` BIGINT, IN `SALES_ORDER_NO` VARCHAR(255), IN `PURCHASE_ORDER_ID` VARCHAR(255), IN `PO_DATE` DATE, IN `CUSTOMER_ID` BIGINT, IN `CUSTOMER_REFERENCE_NO` VARCHAR(255), IN `BILLING_ADDRESS` TEXT, IN `SHIPPING_ADDRESS` TEXT, IN `SHIPPING_DESCRIPTION` TEXT, IN `SHIPPING_COST` FLOAT(10,2), IN `TAX_ID` INT, IN `ORDER_DATE` DATE, IN `DELIVERY_DATE` DATE, IN `ADDITIONAL_INFO` TEXT, IN `SALES_ORDER_STATUS_ID` INT, IN `CREATED_BY` BIGINT, IN `SALES_TYPE_ID` BIGINT(20))   BEGIN
IF EXISTS (SELECT 1 FROM tbl_sales_order s WHERE s.sales_order_no = SALES_ORDER_NO AND s.store_id = STORE_ID) THEN
        BEGIN
            SELECT s.sales_order_id,
                   'Sales order no. already exists' AS message,
                   0 AS action
            FROM tbl_sales_order s
            WHERE s.sales_order_no = SALES_ORDER_NO AND s.store_id = STORE_ID;
        END;
    ELSE
        BEGIN
            INSERT INTO tbl_sales_order
                (store_id, sales_order_no, sales_type_id, purchase_order_id, po_date, customer_id, customer_reference_no, billing_address, shipping_address, shipping_description, shipping_cost, tax_id, order_date, delivery_date, additional_info, sales_order_status_id, created_by)
            VALUES
                (STORE_ID, SALES_ORDER_NO, SALES_TYPE_ID, PURCHASE_ORDER_ID, PO_DATE, CUSTOMER_ID, CUSTOMER_REFERENCE_NO, BILLING_ADDRESS, SHIPPING_ADDRESS, SHIPPING_DESCRIPTION, SHIPPING_COST, TAX_ID, ORDER_DATE, DELIVERY_DATE, ADDITIONAL_INFO, SALES_ORDER_STATUS_ID, CREATED_BY);

			SELECT 
					LAST_INSERT_ID() AS sales_order_id
					, 1 AS action, "Sales order no. saved successfully." as message;
        END;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_SALES_ORDER_DETAIL` (IN `SALES_ORDER_ID` BIGINT, IN `PRODUCT_ID` BIGINT, IN `PRODUCT_ALIAS` VARCHAR(255), IN `QUANTITY` BIGINT, IN `PRICE_PER_UNIT` FLOAT(10,2), IN `CREATED_BY` BIGINT, IN `SALES_ORDER_DETAIL_ID` BIGINT(20))   BEGIN	
	INSERT INTO tbl_sales_order_detail(sales_order_id, product_id, product_alias, quantity, price_per_unit, discount_percentage, tax_id,created_by) 
	VALUES(SALES_ORDER_ID, PRODUCT_ID, PRODUCT_ALIAS, QUANTITY, PRICE_PER_UNIT, DISCOUNT_PERCENTAGE, TAX_ID, CREATED_BY);
	
	 UPDATE tbl_box_detail bd SET bd.remaining_item = (bd.remaining_item - QUANTITY) WHERE bd.product_id = PRODUCT_ID; 
	 
	SELECT 	LAST_INSERT_ID() AS sales_order_detail_id
			, 1 AS action, "Sales order detail saved successfully." as message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_SALES_TYPE` (IN `SALES_TYPE_ID` BIGINT(20), IN `SALES_TYPE_NAME` VARCHAR(255), IN `CLIENT_ID` BIGINT(20), IN `IS_DELETED` ENUM('0','1'))   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_sales_type st 
			SET	
				st.is_deleted = IS_DELETED
			WHERE st.sales_type_id = SALES_TYPE_ID;
		
			SELECT  st.sales_type_id        
	        		, 'Sales type deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_sales_type st
	    	WHERE 	st.sales_type_id = SALES_TYPE_ID;
		END;
	ELSEIF(SALES_TYPE_ID <> 0)THEN
		BEGIN
			UPDATE tbl_sales_type st
			SET		
				st.sales_type_name = SALES_TYPE_NAME
			WHERE st.sales_type_id = SALES_TYPE_ID;
		
			SELECT  st.sales_type_id        
	        		, 'Sales type updated successfully!' AS message
	        		, 1 action
		    FROM  	tbl_sales_type st
	    	WHERE 	st.sales_type_id = SALES_TYPE_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_sales_type st WHERE st.sales_type_name = SALES_TYPE_NAME AND st.is_deleted = '0') THEN 
		BEGIN 
			SELECT	st.sales_type_id
					, st.sales_type_name
					, 'Sales type already exists!' AS message
					, 0 action
			FROM 	tbl_sales_type st
			WHERE 	st.sales_type_name = SALES_TYPE_NAME;
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_sales_type(sales_type_id, sales_type_name, is_deleted, client_id)
			VALUES (SALES_TYPE_ID, SALES_TYPE_NAME, IS_DELETED, CLIENT_ID);
			
			SELECT 	LAST_INSERT_ID() sales_type_id
			, 1 AS action, "Sales Type Name saved successfully." as message; 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_SCRAP` (IN `SCRAP_ID` BIGINT, IN `BOX_NO` BIGINT, IN `SCRAP_QUANTITY` BIGINT, IN `QUANTITY` BIGINT, IN `REMARK` VARCHAR(255), IN `CREATED_BY` BIGINT, IN `IS_DELETED` ENUM('1','0'), IN `SCRAP_REMANING_QUANTITY` BIGINT)   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_scrap_list sl 
			SET	
				sl.is_deleted = IS_DELETED
				, sl.created_by = CREATED_BY
			WHERE sl.scrap_id = SCRAP_ID;

			UPDATE tbl_box_detail bd
			INNER JOIN tbl_scrap_list sl ON bd.box_no = sl.scrap_box_no
			SET bd.remaining_item = (bd.remaining_item + sl.scrap_qty)
			WHERE bd.box_no = BOX_NO AND sl.scrap_id = SCRAP_ID;

			SELECT  sl.scrap_id         
	        		, 'Scrap deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_scrap_list sl
	    	WHERE 	sl.scrap_id = SCRAP_ID;			
		END;
    
       ELSE
			BEGIN
			INSERT INTO tbl_scrap_list(scrap_id,scrap_box_no, scrap_qty, remaining_item,remark,created_by)
			VALUES (SCRAP_ID, BOX_NO, SCRAP_QUANTITY,QUANTITY,REMARK, CREATED_BY);
			
			UPDATE tbl_box_detail bd SET bd.remaining_item = (QUANTITY-SCRAP_QUANTITY) WHERE bd.box_no = BOX_NO;

			SELECT	sl.scrap_id
            		,sl.scrap_box_no
					,sl.scrap_qty
                   	,sl.remaining_item
                    ,sl.location_id
                    ,sl.remark
					, 'Scrap detail saved successfully!' AS message
					, 1 action
			FROM 	tbl_scrap_list sl
			WHERE 	sl.scrap_id = LAST_INSERT_ID(); 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_SFG` (IN `SFG_ID` BIGINT(20), IN `SFG_NAME` VARCHAR(255), IN `SFG_CODE` VARCHAR(255), IN `CATEGORY_ID` BIGINT(20), IN `SUBCATEGORY_ID` BIGINT(20), IN `HSN_CODE` BIGINT(20), IN `ADDITIONAL_INFO` TEXT, IN `IS_DELETED` ENUM('1','0'), IN `CREATED_BY` INT(11), IN `CREATED_DATE` TIMESTAMP, IN `STORE_ID` BIGINT(20), IN `WEIGHT` FLOAT(10,2), IN `SUSTAINABILITY_SCORE` VARCHAR(255), IN `UNIT_ID` BIGINT(20))   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_sfg s
			SET	s.is_deleted = IS_DELETED
			WHERE s.sfg_id = SFG_ID;
		
			SELECT  s.sfg_id       
	        		, 'Sfg deleted successfully' AS message
	        		, 1 action
		    FROM  	tbl_sfg s
	    	WHERE 	s.sfg_id = SFG_ID;
		END;	
	ELSEIF(SFG_ID <> 0)THEN
		BEGIN
			UPDATE tbl_sfg s
			SET	
				s.category_id = CATEGORY_ID	
				, s.subcategory_id = SUBCATEGORY_ID
				, s.hsn_code = HSN_CODE
				, s.sfg_code = SFG_CODE				
				, s.sfg_name = SFG_NAME
				, s.additional_info = ADDITIONAL_INFO
				, s.weight = WEIGHT
                , s.unit_id = UNIT_ID
				, s.sustainability_score = SUSTAINABILITY_SCORE
				, s.created_by = CREATED_BY
			WHERE s.sfg_id = SFG_ID;
			SELECT  s.sfg_id      
	        		, 'Sfg detail updated successfully' AS message
	        		, 1 action
		    FROM  	tbl_sfg s
	    	WHERE 	s.sfg_id = SFG_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_sfg s WHERE s.sfg_name = SFG_NAME) THEN 
  		BEGIN 
	    	SELECT  s.sfg_id       
	        		, 'Sfg name already exists' AS message
	        		, 0 action
		    FROM  	tbl_sfg s
	    	WHERE 	s.sfg_name = SFG_NAME;
		END;
  	ELSE
    	BEGIN
      		INSERT INTO tbl_sfg(sfg_name, sfg_code, category_id, subcategory_id, hsn_code,additional_info,created_by, created_date, store_id,weight,sustainability_score, unit_id) 
      		VALUES (SFG_NAME, SFG_CODE, CATEGORY_ID, SUBCATEGORY_ID ,HSN_CODE, ADDITIONAL_INFO,CREATED_BY, CREATED_DATE, STORE_ID, WEIGHT, SUSTAINABILITY_SCORE, UNIT_ID);
      
      		SELECT LAST_INSERT_ID() AS sfg_id,1 AS action, "Sfg detail saved successfully" as message;      
	    END;
  	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_SFG_BOX_DETAIL` (IN `STORE_ID` BIGINT, IN `SFG_ID` BIGINT, IN `SFG_GRN_ID` BIGINT, IN `SFG_GRN_DETAIL_ID` BIGINT, IN `BOX_NAME` VARCHAR(255), IN `NO_OF_ITEMS` BIGINT, IN `CREATED_BY` BIGINT, IN `GRN_TYPE` VARCHAR(255))   BEGIN	
    INSERT INTO tbl_sfg_box_detail (
        store_id, sfg_id, sfg_grn_id, sfg_grn_detail_id, box_name, no_of_items,remaining_item, created_by, grn_type
    ) 
    VALUES (
        STORE_ID, SFG_ID, SFG_GRN_ID, SFG_GRN_DETAIL_ID, BOX_NAME, NO_OF_ITEMS,NO_OF_ITEMS, CREATED_BY, GRN_TYPE
    );

    UPDATE tbl_sfg_box_detail b 
    SET b.box_no = LAST_INSERT_ID() + 10000 
    WHERE b.sfg_box_detail_id = LAST_INSERT_ID();

    SELECT  
        b.sfg_box_detail_id,
        b.sfg_grn_id,
        b.sfg_grn_detail_id,
        b.box_name,
        b.box_no,
        b.no_of_items,
        b.created_by,
        b.created_date,					
        'SFG Box detail save successfully' AS message,
        1 AS action
    FROM tbl_sfg_box_detail b
    WHERE b.sfg_box_detail_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_SFG_BOX_LOCATION` (IN `STORE_ID` BIGINT(20), IN `LOCATION_NO` BIGINT(20), IN `BOX_NO` BIGINT(20), IN `CREATED_BY` BIGINT(20), IN `GRN_TYPE` VARCHAR(255))   BEGIN
	IF EXISTS(SELECT 1 FROM tbl_sfg_box_location b WHERE b.box_no = BOX_NO AND b.store_id = STORE_ID) THEN 
  		BEGIN 
	    	SELECT  b.sfg_box_location_id
	        		, 'Put away process already completed for this box!' AS message
	        		, 0 action
		    FROM  	tbl_sfg_box_location b
	    	WHERE 	b.box_no = BOX_NO AND b.store_id = STORE_ID;
		END; 
	ELSE
    	BEGIN
			INSERT INTO tbl_sfg_box_location(store_id, grn_type, location_no, box_no, created_by) 
			VALUES (STORE_ID, GRN_TYPE, LOCATION_NO, BOX_NO, CREATED_BY);
			
			UPDATE tbl_sfg_box_detail tbd SET tbd.is_put_away = '1' WHERE tbd.box_no = BOX_NO; 
		
			SELECT  b.sfg_box_location_id
	        		, 'Put away process completed for this box!' AS message
	        		, 1 action
		    FROM  	tbl_sfg_box_location b
	    	WHERE 	b.box_no = BOX_NO AND b.store_id = STORE_ID;
		END;
	END IF;	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_SFG_GRN` (IN `STORE_ID` BIGINT, IN `SFG_GRN_ID` BIGINT, IN `SFG_GRN_NO` VARCHAR(255), IN `ADDITIONAL_INFO` TEXT, IN `CREATED_BY` BIGINT, IN `PO_NO` BIGINT, IN `PO_DATE` DATE, IN `QUALITY_CHECK` ENUM('1','0'), IN `GRN_TYPE` VARCHAR(255))   BEGIN
	IF(QUALITY_CHECK <> 0 && SFG_GRN_ID <> 0)THEN
		BEGIN
			UPDATE tbl_sfg_grn s
			SET	s.is_sfg_quality_checked = QUALITY_CHECK
			WHERE s.sfg_grn_id = SFG_GRN_ID;
		
			SELECT SFG_GRN_ID, 1 AS action;
	
		END;
  	ELSE 
		BEGIN
			INSERT INTO `tbl_sfg_grn`(store_id, sfg_grn_no, po_no, po_date, additional_info, created_by,grn_type) 
			VALUES (STORE_ID, SFG_GRN_NO,PO_NO ,PO_DATE , ADDITIONAL_INFO, CREATED_BY, GRN_TYPE);
		
			SELECT 
					LAST_INSERT_ID() AS sfg_grn_id
					, 1 AS action, "Sfg grn no. saved successfully." as message;
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_SFG_GRN_DETAIL` (IN `SFG_GRN_ID` BIGINT(20), IN `HSN_CODE` VARCHAR(255), IN `QUANTITY` BIGINT(20), IN `INWARD_UNIT_ID` VARCHAR(255), IN `RATE` FLOAT(10,2), IN `AMOUNT` FLOAT(10,2), IN `TAX_ID` BIGINT(20), IN `AFTER_TAX` FLOAT(10,2), IN `NO_OF_BOXES` BIGINT(20), IN `CREATED_BY` BIGINT(20), IN `SFG_ID` BIGINT(20), IN `SUPPLIER_ID` BIGINT(20))   BEGIN
	INSERT INTO tbl_sfg_grn_detail
	(sfg_grn_id, sfg_id,supplier_id, hsn_code, quantity, inward_unit_id, rate, amount, tax_id, after_tax, no_of_boxes,created_by) 
	VALUES 
	(SFG_GRN_ID, SFG_ID,SUPPLIER_ID ,HSN_CODE, QUANTITY, INWARD_UNIT_ID, RATE, AMOUNT, TAX_ID, AFTER_TAX, NO_OF_BOXES, CREATED_BY);

	SELECT 	LAST_INSERT_ID() AS sfg_grn_detail_id
			, 1 AS action, "Sfg grn no. saved successfully." as message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_SFG_QUALITY_CHECK` (IN `SFG_QUALITY_CHECKED_BOXES` BIGINT(20), IN `SFG_QUALITY_CHECKED_ITEM` BIGINT(20), IN `PURCHASE_PRICE_PER_ITEM` BIGINT(20), IN `SFG_GRN_DETAIL_ID` BIGINT(20))   BEGIN
    IF SFG_GRN_DETAIL_ID IS NOT NULL AND SFG_GRN_DETAIL_ID > 0 THEN
        UPDATE tbl_sfg_grn_detail trgd
        SET 
            sfg_quality_checked_boxes = SFG_QUALITY_CHECKED_BOXES,
            sfg_quality_checked_item = SFG_QUALITY_CHECKED_ITEM,
            purchase_price_per_item = PURCHASE_PRICE_PER_ITEM
        WHERE trgd.sfg_grn_detail_id = SFG_GRN_DETAIL_ID;

        SELECT 'Quality checked done successfully.' AS message, 1 AS action;
    ELSE
        SELECT 'Invalid rm_grn_detail_id' AS message, 0 AS action;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_SIZE` (IN `SIZE_ID` BIGINT, IN `STORE_ID` BIGINT, IN `CATEGORY_ID` BIGINT, IN `SIZE_NAME` VARCHAR(255), IN `IS_DELETED` ENUM('1','0'), IN `CREATED_BY` BIGINT)   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_size s 
			SET	
				s.is_deleted = IS_DELETED
				, s.created_by = CREATED_BY
			WHERE s.size_id = SIZE_ID;
		
			SELECT  s.size_id         
	        		, 'Size deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_size s
	    	WHERE 	s.size_id = SIZE_ID;
		END;
	ELSEIF(SIZE_ID <> 0)THEN
		BEGIN
			UPDATE tbl_size s 
			SET		
				s.size = SIZE_NAME
				, s.category_id = CATEGORY_ID
				, s.created_by = CREATED_BY
			WHERE s.size_id = SIZE_ID;
		
			SELECT  s.size_id         
	        		, 'Size updated successfully!' AS message
	        		, 1 action
		    FROM  	tbl_size s
	    	WHERE 	s.size_id = SIZE_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_size s WHERE s.size = SIZE_NAME AND s.is_deleted = '0') THEN 
		BEGIN 
			SELECT	s.size_id
					, s.size
					, 'Size detail already exists!' AS message
					, 0 action
			FROM 	tbl_size s
			WHERE 	s.size = SIZE_NAME;
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_size(store_id, category_id, size, created_by)
			VALUES (STORE_ID, CATEGORY_ID, SIZE_NAME, CREATED_BY);
			
			SELECT	s.size_id
					, s.size
					, 'Size detail saved successfully!' AS message
					, 1 action
			FROM 	tbl_size s
			WHERE 	s.size_id = LAST_INSERT_ID(); 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_STOCK_AUDIT` (IN `LOCATION_NO` BIGINT(20), IN `BOX_NO` BIGINT(20), IN `CREATED_BY` BIGINT(20))   BEGIN
        INSERT INTO tbl_stock_audit(location_no, box_no, created_by)
        VALUES (LOCATION_NO, BOX_NO, CREATED_BY);

        SELECT 
            LAST_INSERT_ID() AS stock_audit_id,
            1 AS action,
            'Stock audit list saved successfully.' AS message;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_STORE` (IN `STORE_ID` BIGINT, IN `BRANCH_ID` BIGINT, IN `STORE_NAME` VARCHAR(255), IN `CREATED_BY` BIGINT, IN `IS_DELETED` ENUM('1','0'))   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_stores s 
			SET	
				s.is_deleted = IS_DELETED
				, s.created_by = CREATED_BY
			WHERE s.store_id = STORE_ID;
		
			SELECT  s.store_id         
	        		, 'Store deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_stores s
	    	WHERE 	s.store_id = STORE_ID;
		END;
	ELSEIF(STORE_ID <> 0)THEN
		BEGIN
			UPDATE tbl_stores s 
			SET		
				s.name = STORE_NAME
				, s.branch_id = BRANCH_ID
				, s.created_by = CREATED_BY
			WHERE s.store_id = STORE_ID;
		
			SELECT  s.store_id         
	        		, 'Store deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_stores s
	    	WHERE 	s.store_id = STORE_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_stores s WHERE s.store_name = STORE_NAME AND s.is_deleted = '0') THEN 
		BEGIN 
			SELECT	s.store_id
					, s.store_name
					, 'Store detail already exists!' AS message
					, 0 action
			FROM 	tbl_stores s
			WHERE 	s.store_name = STORE_NAME;
		END;	
	ELSE
		BEGIN
			 INSERT INTO tbl_stores(branch_id, store_name, created_by) 
      VALUES (BRANCH_ID, STORE_NAME, CREATED_BY);
			
			SELECT	s.store_id
					, s.store_name
					, 'Store detail saved successfully!' AS message
					, 1 action
			FROM 	tbl_stores s
			WHERE 	s.store_id = LAST_INSERT_ID(); 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_SUBCATEGORY` (IN `SUBCATEGORY_ID` BIGINT, IN `STORE_ID` BIGINT, IN `CATEGORY_ID` BIGINT, IN `SUBCATEGORY_NAME` VARCHAR(255), IN `IS_DELETED` ENUM('1','0'), IN `CREATED_BY` BIGINT)   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_subcategory sc 
			SET	
				sc.is_delete = IS_DELETED
				, sc.created_by = CREATED_BY
			WHERE sc.subcategory_id = SUBCATEGORY_ID;
		
			SELECT  sc.subcategory_id         
	        		, 'Subcategory deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_subcategory sc
	    	WHERE 	sc.subcategory_id = SUBCATEGORY_ID;
		END;
	ELSEIF(SUBCATEGORY_ID <> 0)THEN
		BEGIN
			UPDATE tbl_subcategory sc
			SET		
				sc.subcategory = SUBCATEGORY_NAME
				, sc.category_id = CATEGORY_ID
				, sc.created_by = CREATED_BY
			WHERE sc.subcategory_id = SUBCATEGORY_ID;
			SELECT  sc.subcategory_id          
	        		, 'Subcategory updated successfully!' AS message
	        		, 1 action
		    FROM  	tbl_subcategory sc
	    	WHERE 	sc.subcategory_id = SUBCATEGORY_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_subcategory sc WHERE sc.subcategory = SUBCATEGORY_NAME AND sc.category_id = CATEGORY_ID AND sc.is_delete = '0') THEN 
		BEGIN 
			SELECT	sc.subcategory_id
					, sc.subcategory
					, 'Subcategory detail already exists!' AS message
					, 0 action
			FROM 	tbl_subcategory sc
			WHERE 	sc.subcategory = SUBCATEGORY_NAME;
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_subcategory(store_id, category_id, subcategory, created_by)
			VALUES (STORE_ID, CATEGORY_ID, SUBCATEGORY_NAME, CREATED_BY);
			
			SELECT	sc.subcategory_id
					, sc.subcategory
					, 'Subcategory detail saved successfully!' AS message
					, 1 action
			FROM 	tbl_subcategory sc
			WHERE 	sc.subcategory_id = LAST_INSERT_ID(); 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_SUPPLIER` (IN `SUPPLIER_ID` BIGINT, IN `COMPANY_NAME` VARCHAR(255), IN `EMAIL` VARCHAR(255), IN `MOBILE_NO` BIGINT(20), IN `ADDRESS` TEXT, IN `PAN_NO` VARCHAR(255), IN `GST_NO` VARCHAR(255), IN `IS_OEM` ENUM('1','0'), IN `COMMENTS` TEXT, IN `CREATED_BY` INT(10))   BEGIN  
  IF(SUPPLIER_ID<>0) THEN 
    BEGIN
      UPDATE tbl_supplier s
      SET 
        s.company_name=COMPANY_NAME
        ,s.email=EMAIL
        ,s.mobile_no=MOBILE_NO
        ,s.address=ADDRESS
        ,s.pan_no=PAN_NO
        ,s.gst_no=GST_NO
        ,s.additional_comments=COMMENTS
        ,s.created_by=CREATED_BY
      WHERE s.supplier_id=SUPPLIER_ID;
      SELECT SUPPLIER_ID AS supplier_id, 1 AS action, "Data updated successfully" as message;
    END; 
  ELSEIF EXISTS(SELECT 1 FROM tbl_supplier s WHERE s.company_name = COMPANY_NAME AND s.is_deleted = '0') THEN 
		BEGIN 
			SELECT	s.supplier_id
					, s.company_name
					, 'Vendor detail already exists!' AS message
					, 0 action
			FROM 	tbl_supplier s
			WHERE 	s.company_name = COMPANY_NAME;
		END;	
	ELSE
    BEGIN
      INSERT INTO tbl_supplier(company_name, email, mobile_no, address, pan_no, gst_no, is_oem, additional_comments, created_by) 
      VALUES (COMPANY_NAME, EMAIL, MOBILE_NO, ADDRESS, PAN_NO, GST_NO, IS_OEM, COMMENTS, CREATED_BY);
      
      SELECT LAST_INSERT_ID() AS supplier_id,1 AS action, "Data save successfully" as message;      
    END;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_TAX_RATE` (IN `TAX_ID` VARCHAR(255), IN `TAX_NAME` VARCHAR(255), IN `TAX_RATE` FLOAT, IN `CREATED_BY` INT, IN `IS_DELETED` ENUM('1','0'))   BEGIN
    IF(IS_DELETED <> '0') THEN
        -- Deletion block: Mark the tax as deleted
        UPDATE tbl_tax t 
        SET    
            t.is_deleted = IS_DELETED,
            t.created_by = CREATED_BY
        WHERE t.tax_id = TAX_ID;
        
        SELECT  
            t.tax_id,       
            'Tax deleted successfully!' AS message,
            1 AS action
        FROM   tbl_tax t
        WHERE  t.tax_id = TAX_ID;
    
    ELSEIF (TAX_ID <> 0) THEN
        -- Update block: Update tax information
        UPDATE tbl_tax t 
        SET        
            t.tax_name = TAX_NAME,
            t.created_by = CREATED_BY
        WHERE t.tax_id = TAX_ID;
        
        SELECT  
            t.tax_id,         
            'Tax updated successfully!' AS message,
            1 AS action
        FROM   tbl_tax t
        WHERE  t.tax_id = TAX_ID;
    
    ELSEIF EXISTS (SELECT 1 FROM tbl_tax t WHERE t.tax_rate = TAX_RATE AND t.is_deleted = '1') THEN
        -- Check if tax rate already exists (soft deleted)
        SELECT 
            t.tax_id,
            t.tax_name,
            t.tax_rate,
            t.status,
            t.created_by,
            t.created_date,            
            'Tax rate already exists' AS message,
            0 AS action
        FROM  tbl_tax t
        WHERE  t.tax_rate = TAX_RATE AND t.is_deleted = '1';
    
    ELSE
        -- Insert new tax rate
        INSERT INTO tbl_tax (tax_name, tax_rate, created_by) 
        VALUES (TAX_NAME, TAX_RATE, CREATED_BY);
        
        SELECT  
            t.tax_id,
            t.tax_name,
            t.tax_rate,
            t.status,
            t.created_by,
            t.created_date,            
            'Tax rate saved successfully' AS message, 
            1 AS action
        FROM  tbl_tax t            
        WHERE  t.tax_id = LAST_INSERT_ID(); 
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_TROLLEY_TYPE` (IN `TROLLEY_TYPE` VARCHAR(255), IN `CREATED_BY` INT)   BEGIN
	IF EXISTS(SELECT 1 FROM tbl_trolley_type T WHERE T.trolley_type = TROLLEY_TYPE) THEN 
		BEGIN 
			SELECT 	T.trolley_type_id
					, T.trolley_type 
					, 'Trolley type already exists' AS message
					, 0 action
			FROM 	tbl_trolley_type T
			WHERE 	T.trolley_type = TROLLEY_TYPE;
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_trolley_type(trolley_type,created_by) 
			VALUES (TROLLEY_TYPE,CREATED_BY);
			
			SELECT	T.trolley_type_id
					, T.trolley_type					
					, 'Trolley type save successfully' AS message
					, 1 action
			FROM 	tbl_trolley_type T
			WHERE 	T.trolley_type_id = LAST_INSERT_ID(); 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_UNIT_CONVERSION` (IN `UNIT_CONVERSION_ID` BIGINT, IN `CLIENT_ID` BIGINT, IN `FROM_UNIT_ID` BIGINT, IN `FROM_UNIT_VALUE` FLOAT(10,2), IN `TO_UNIT_ID` BIGINT, IN `TO_UNIT_VALUE` FLOAT(10,2), IN `IS_DELETED` ENUM('1','0'), IN `CREATED_BY` BIGINT)   BEGIN 
	
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_unit_conversion uc 
			SET	
				uc.is_deleted = IS_DELETED
				, uc.created_by = CREATED_BY
			WHERE uc.unit_conversion_id = UNIT_CONVERSION_ID AND uc.client_id = CLIENT_ID;
		
			SELECT  uc.unit_conversion_id         
	        		, 'Unit conversion deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_unit_conversion uc 
	    	WHERE uc.unit_conversion_id = UNIT_CONVERSION_ID AND uc.client_id = CLIENT_ID;
		END;
	ELSEIF EXISTS
		(
			SELECT 1 
				FROM tbl_unit_conversion uc 
				WHERE 	uc.from_unit_id = FROM_UNIT_ID
						AND uc.from_unit_value = FROM_UNIT_VALUE
						AND uc.to_unit_id = TO_UNIT_ID
						AND uc.to_unit_value = TO_UNIT_VALUE
						AND uc.client_id = CLIENT_ID 
						AND uc.is_deleted = '0'
		) THEN 
	
		BEGIN 
			SELECT  uc.unit_conversion_id         
	        		, 'Unit conversion already exists!' AS message
	        		, 0 action
		    FROM  	tbl_unit_conversion uc 
	    	WHERE 	uc.from_unit_id = FROM_UNIT_ID
						AND uc.from_unit_value = FROM_UNIT_VALUE
						AND uc.to_unit_id = TO_UNIT_ID
						AND uc.to_unit_value = TO_UNIT_VALUE
						AND uc.client_id = CLIENT_ID 
						AND uc.is_deleted = '0';
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_unit_conversion
				(client_id, from_unit_id, from_unit_value, to_unit_id, to_unit_value, is_deleted, created_by) 
			VALUES 
				(CLIENT_ID, FROM_UNIT_ID, FROM_UNIT_VALUE, TO_UNIT_ID, TO_UNIT_VALUE, IS_DELETED, CREATED_BY);
			
			SELECT	u.unit_conversion_id
					, 'Unit conversion detail saved successfully!' AS message
					, 1 action
			FROM 	tbl_unit_conversion u
			WHERE 	u.unit_conversion_id = LAST_INSERT_ID(); 
		END;	
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_UNIT_OF_MEASURE` (IN `UNIT_ID` BIGINT, IN `STORE_ID` BIGINT, IN `UNIT` VARCHAR(255), IN `IS_DELETED` ENUM('1','0'), IN `CREATED_BY` INT)   BEGIN
	IF(IS_DELETED <> '0')THEN
		BEGIN
			UPDATE tbl_unit_of_measure m 
			SET	
				m.is_deleted = IS_DELETED
				, m.created_by = CREATED_BY
			WHERE m.unit_id = UNIT_ID;
		
			SELECT  m.unit_id         
	        		, 'Unit deleted successfully!' AS message
	        		, 1 action
		    FROM  	tbl_unit_of_measure m
	    	WHERE 	m.unit_id = UNIT_ID;
		END;
	ELSEIF EXISTS(SELECT 1 FROM tbl_unit_of_measure m WHERE m.unit = UNIT AND m.is_deleted = '0') THEN 
		BEGIN 
			SELECT 	m.unit_id
					, m.unit
					, m.status
					, 'Unit already exists' AS message
					, 0 action
					, m.created_date
			FROM 	tbl_unit_of_measure m
			WHERE 	m.unit = UNIT;
		END;	
	ELSE
		BEGIN
			INSERT INTO tbl_unit_of_measure(store_id,unit,created_by) 
			VALUES (STORE_ID,UNIT,CREATED_BY);
			
			SELECT	m.unit_id
					, m.unit
					, 'Unit save successfully' AS message
					, 1 action
					, m.status
					, m.created_date
			FROM 	tbl_unit_of_measure m
			WHERE 	m.unit_id = LAST_INSERT_ID(); 		
		END;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_USER_DETAIL` (IN `NAME` VARCHAR(255), IN `EMAIL` VARCHAR(255), IN `MOBILE_NO` BIGINT(20), IN `PASSWORD` VARCHAR(255), IN `ADDRESS` TEXT, IN `CREATED_BY` BIGINT(20), IN `USER_TYPE` INT(10), IN `USER_ID` BIGINT(20))   BEGIN
	IF(USER_ID<>0) THEN 
		BEGIN
			UPDATE tbl_user u SET u.name=NAME, u.email=EMAIL, u.address= ADDRESS, u.mobile_no=MOBILE_NO 
			WHERE u.user_id=USER_ID;
			SELECT USER_ID AS user_id,1 AS Action, "Data updates successfully" as message;
		END; 
	ELSE
		BEGIN
			INSERT INTO tbl_user(name, email, address, mobile_no, user_type, created_by) 
			VALUES (NAME,EMAIL,ADDRESS,MOBILE_NO,USER_TYPE,CREATED_BY);
			SELECT LAST_INSERT_ID() AS ID,1 AS Action, "Data save successfully" as message;
		END;		
		IF(LAST_INSERT_ID() > 0) THEN
			BEGIN
				INSERT INTO tbl_login(email, mobile_no, password, user_id) 
				VALUES (EMAIL,MOBILE_NO,PASSWORD,LAST_INSERT_ID());
			END;	
		END IF;
	END IF;
	SELECT 	U.user_id
			, U.name
			, U.email
			, U.address
			, U.mobile_no
			, U.user_type
			, U.created_by 
	FROM tbl_user U 
	WHERE U.user_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_USER_PERMISSION` (IN `USER_ID` BIGINT, IN `USER_TYPE` INT, IN `MODULE_ID` INT, IN `VIEW_PERMISSION` INT, IN `DELETE_PERMISSION` INT, IN `CREATE_PERMISSION` INT, IN `UPDATE_PERMISSION` INT, IN `CREATED_BY` INT, IN `CREATED_DATE` TIMESTAMP, IN `USER_PERMISSION_ID` BIGINT)   BEGIN
	IF(USER_PERMISSION_ID = 0) THEN
		BEGIN
			INSERT INTO `tbl_user_permission`(`user_id`, `user_type`, `module_id`, `view_permission`, `delete_permission`, `create_permission`, `update_permission`, `created_date`, `created_by`) 
			VALUES (USER_ID,USER_TYPE,MODULE_ID,VIEW_PERMISSION,DELETE_PERMISSION,CREATE_PERMISSION,UPDATE_PERMISSION,CREATED_DATE,CREATED_BY);
		
			SELECT LAST_INSERT_ID() AS id, 'User Permission Saved Successfully' AS message;		
		END;
	ELSE	
		BEGIN
			UPDATE `tbl_user_permission` 
			SET 
			`user_id`=USER_ID
			,`user_type`=USER_TYPE
			,`module_id`=MODULE_ID
			,`view_permission`= VIEW_PERMISSION
			,`delete_permission`=DELETE_PERMISSION
			,`create_permission`=CREATE_PERMISSION
			,`update_permission`=UPDATE_PERMISSION
			,`modified_by`=CREATED_BY
			,`modified_date`=CREATED_DATE 
			WHERE id=USER_PERMISSION_ID;
		
			SELECT USER_PERMISSION_ID AS id, 'User Permission Updated Successfully' AS message;
	
		END;
	END	IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SAVE_VARIANT` (IN `VARIANT_TITLE` VARCHAR(255), IN `VARIANT_SLUG` VARCHAR(255), IN `VARIANT_OPTION` VARCHAR(255), IN `CREATED_BY` BIGINT)   BEGIN
  IF EXISTS(SELECT 1 FROM tbl_variant v WHERE v.variant_title = VARIANT_TITLE) THEN 
  BEGIN 
    SELECT  v.variant_id
        , v.variant_title 
        , 'Variant detail already exists' AS message
        , 0 action
    FROM  tbl_variant v
    WHERE   v.variant_title = VARIANT_TITLE;
  END;
  ELSE
    BEGIN
      INSERT INTO tbl_variant(variant_title, slug, variant_option, created_by) 

      VALUES (VARIANT_TITLE, VARIANT_SLUG, VARIANT_OPTION, CREATED_BY);
      
      SELECT LAST_INSERT_ID() AS variant_id,1 AS action, "Variant detail saved successfully" as message;      
    END;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_create_bom_batch` (IN `FG_ID` INT, IN `STORE_ID` INT, IN `GRN_TYPE` TEXT, IN `ITEM_IDS` TEXT, IN `QUANTITIES` TEXT, IN `CREATED_BY` INT)   BEGIN
    DECLARE BOM_EXISTS INT DEFAULT 0;

    -- Check if BOM already exists
    SELECT COUNT(*) INTO BOM_EXISTS
    FROM tbl_bom
    WHERE fg_id = FG_ID AND is_deleted = 0;

    IF BOM_EXISTS > 0 THEN
        SELECT
            NULL AS bom_id,
            'BOM already exists for the selected FG!' AS message,
            0 AS action;
    ELSE
        BEGIN
            DECLARE item_id VARCHAR(255);
            DECLARE qty VARCHAR(255);
            DECLARE i INT DEFAULT 1;
            DECLARE item_count INT;

            SET item_count = JSON_LENGTH(ITEM_IDS);

            WHILE i <= item_count DO
                SET item_id = JSON_UNQUOTE(JSON_EXTRACT(ITEM_IDS, CONCAT('$[', i - 1, ']')));
                SET qty = JSON_UNQUOTE(JSON_EXTRACT(QUANTITIES, CONCAT('$[', i - 1, ']')));

                INSERT INTO tbl_bom(store_id, grn_type, fg_id, item_id, quantity, created_by)
                VALUES (STORE_ID, GRN_TYPE, FG_ID, item_id, qty, CREATED_BY);

                SET i = i + 1;
            END WHILE;

            SELECT
                LAST_INSERT_ID() AS bom_id,
                'BOM materials added successfully!' AS message,
                1 AS action;
        END;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UPDATE_GENERAL_SETTING` (IN `SETTINGS_ID` INT, IN `CURRENCY_CODE_ID` INT, IN `SALES_ORDER` INT, IN `PURCHASE_ORDER` INT)   BEGIN
	UPDATE tbl_settings s 
	SET 
		s.currency_code_id = CURRENCY_CODE_ID
		, s.default_delivery_time_sales_order = SALES_ORDER
		, s.default_lead_time_purchase_order = PURCHASE_ORDER
	WHERE s.settings_id = SETTINGS_ID;

	SELECT 
		s.settings_id
		, s.currency_code_id
		, s.default_delivery_time_sales_order
		, s.default_lead_time_purchase_order
		, 'General settings updated successfully' AS message 
		, 1 action
	FROM tbl_settings s
	WHERE s.settings_id = SETTINGS_ID;	
		
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `GET_RANDOM_STRING` (`length` SMALLINT(3)) RETURNS VARCHAR(100) CHARSET utf8 COLLATE utf8_general_ci  begin
    SET @returnStr = '';
    SET @allowedChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    SET @i = 0;
    WHILE (@i < length) DO
        SET @returnStr = CONCAT(@returnStr, substring(@allowedChars, FLOOR(RAND() * LENGTH(@allowedChars) + 1), 1));
        SET @i = @i + 1;
    END WHILE;
    RETURN @returnStr;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `mobile_no` bigint(20) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `identity_no` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `register_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `name`, `email`, `address`, `mobile_no`, `city`, `identity_no`, `created_by`, `status`, `is_deleted`, `register_date`) VALUES
(5, 'Admin', 'admin@admin.com', NULL, 9876543210, NULL, NULL, NULL, '1', '0', '2024-01-12 02:09:51');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_module`
--

CREATE TABLE `tbl_admin_module` (
  `admin_module_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `module_name` varchar(255) DEFAULT NULL,
  `menu_title` varchar(255) DEFAULT NULL,
  `access_url` varchar(255) NOT NULL,
  `order_by` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_admin_module`
--

INSERT INTO `tbl_admin_module` (`admin_module_id`, `parent_id`, `module_name`, `menu_title`, `access_url`, `order_by`, `status`, `created_date`) VALUES
(26, 0, 'Settings', 'Settings', '#', 1, '1', '2024-01-12 03:05:34');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bom`
--

CREATE TABLE `tbl_bom` (
  `bom_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `grn_type` varchar(255) DEFAULT NULL,
  `fg_id` bigint(20) DEFAULT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  `quantity` bigint(20) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



-- --------------------------------------------------------

--
-- Table structure for table `tbl_box_detail`
--

CREATE TABLE `tbl_box_detail` (
  `box_detail_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `product_grn_id` bigint(20) DEFAULT NULL,
  `product_grn_detail_id` bigint(20) DEFAULT NULL,
  `box_name` varchar(255) DEFAULT NULL,
  `box_no` varchar(255) DEFAULT NULL,
  `no_of_items` int(11) DEFAULT NULL,
  `remaining_item` int(11) NOT NULL DEFAULT 0,
  `is_put_away` enum('1','0') NOT NULL DEFAULT '0' COMMENT '0 => Put away pending, 1 => Put away done',
  `is_rm` enum('0','1') NOT NULL DEFAULT '0',
  `created_by` bigint(20) DEFAULT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_box_detail_old`
--

CREATE TABLE `tbl_box_detail_old` (
  `box_detail_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `product_grn_id` bigint(20) DEFAULT NULL,
  `product_grn_detail_id` bigint(20) DEFAULT NULL,
  `box_name` varchar(255) DEFAULT NULL,
  `box_no` varchar(255) DEFAULT NULL,
  `no_of_items` int(11) DEFAULT NULL,
  `remaining_item` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) DEFAULT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_box_location`
--

CREATE TABLE `tbl_box_location` (
  `box_location_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `location_no` bigint(20) NOT NULL,
  `box_no` bigint(20) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_branch`
--

CREATE TABLE `tbl_branch` (
  `branch_id` int(11) NOT NULL,
  `client_id` bigint(20) DEFAULT NULL,
  `branch_name` varchar(255) NOT NULL,
  `legal_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `main_branch` enum('1','0') NOT NULL DEFAULT '0',
  `sell` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `make` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `buy` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_branch`
--

INSERT INTO `tbl_branch` (`branch_id`, `client_id`, `branch_name`, `legal_name`, `address`, `main_branch`, `sell`, `make`, `buy`, `status`, `is_deleted`, `created_by`, `created_date`) VALUES
(7, 1, 'Aligarh', '', '', '1', '', '', '', '1', '0', 1, '2025-09-18 17:36:41');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `category_id` bigint(20) NOT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `hsn_code` varchar(50) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_client`
--

CREATE TABLE `tbl_client` (
  `client_id` bigint(20) NOT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_person_name` varchar(255) DEFAULT NULL,
  `contact_person_email` varchar(255) DEFAULT NULL,
  `contact_person_mobile_no` bigint(20) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_client`
--

INSERT INTO `tbl_client` (`client_id`, `client_name`, `address`, `contact_person_name`, `contact_person_email`, `contact_person_mobile_no`, `status`, `is_deleted`, `created_date`) VALUES
(1, 'JSK NETCOMMERCE PVT. LTD.', '101A, 1st Floor, Kundan Kutir, Behind Nafed Building, Ashram, New Delhi-110014 ', 'Gautam Rastogi', 'cx@jsknet.co.in', 1147350101, '1', '0', '2024-02-17 18:41:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contacts`
--

CREATE TABLE `tbl_contacts` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `post_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_country`
--

CREATE TABLE `tbl_country` (
  `country_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `iso_code_2` varchar(2) NOT NULL DEFAULT '',
  `iso_code_3` varchar(3) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tbl_country`
--

INSERT INTO `tbl_country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `status`) VALUES
(1, 'India', 'IN', 'IND', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_coupon_code`
--

CREATE TABLE `tbl_coupon_code` (
  `coupon_code_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `discount_percent` int(11) NOT NULL,
  `flat_amount` int(11) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `isFeatured` enum('1','0') NOT NULL DEFAULT '0',
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `isDeleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_currency`
--

CREATE TABLE `tbl_currency` (
  `currency_id` int(11) NOT NULL,
  `currency_code` varchar(255) DEFAULT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_currency`
--

INSERT INTO `tbl_currency` (`currency_id`, `currency_code`, `is_deleted`) VALUES
(1, 'INR', '0'),
(2, 'USD', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `customer_id` bigint(20) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `mobile_no` bigint(20) DEFAULT NULL,
  `pan_no` varchar(255) DEFAULT NULL,
  `gst_no` varchar(255) DEFAULT NULL,
  `additional_comments` text DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `register_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_default_tax_rate`
--

CREATE TABLE `tbl_default_tax_rate` (
  `default_tax_id` bigint(20) NOT NULL,
  `sales_order_tax` bigint(20) DEFAULT NULL,
  `purchase_order_tax` bigint(20) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



-- --------------------------------------------------------

--
-- Table structure for table `tbl_department`
--

CREATE TABLE `tbl_department` (
  `department_id` bigint(20) NOT NULL,
  `department_name` varchar(255) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee`
--

CREATE TABLE `tbl_employee` (
  `employee_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `department_id` bigint(20) DEFAULT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `employee_email` varchar(255) DEFAULT NULL,
  `employee_mobile_no` bigint(20) DEFAULT NULL,
  `employee_gender` varchar(20) DEFAULT NULL,
  `employee_address` text DEFAULT NULL,
  `employee_designation` varchar(255) DEFAULT NULL,
  `employee_dob` date DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


-- --------------------------------------------------------

--
-- Table structure for table `tbl_fg`
--

CREATE TABLE `tbl_fg` (
  `fg_id` bigint(20) NOT NULL,
  `fg_code` varchar(255) DEFAULT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `sales_qty` varchar(255) DEFAULT NULL,
  `fg_discription` text DEFAULT NULL,
  `is_bom` enum('0','1') NOT NULL DEFAULT '0',
  `is_deleted` enum('1','0') DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



-- --------------------------------------------------------

--
-- Table structure for table `tbl_general_issues`
--

CREATE TABLE `tbl_general_issues` (
  `general_issues_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `general_issues_no` varchar(255) DEFAULT NULL,
  `purchase_order_id` varchar(255) DEFAULT NULL,
  `fg_id` bigint(20) DEFAULT NULL,
  `fg_quantity` bigint(20) DEFAULT NULL,
  `no_of_rm` bigint(20) DEFAULT NULL,
  `department_id` bigint(20) DEFAULT NULL,
  `employee_id` bigint(20) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_dispatch` enum('0','1') DEFAULT '0',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Table structure for table `tbl_grn_type`
--

CREATE TABLE `tbl_grn_type` (
  `grn_type_id` bigint(20) NOT NULL,
  `grn_type_name` varchar(255) DEFAULT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `client_id` bigint(20) DEFAULT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice`
--

CREATE TABLE `tbl_invoice` (
  `invoice_id` bigint(20) NOT NULL,
  `invoice_name` varchar(255) NOT NULL,
  `client_id` bigint(20) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_locations`
--

CREATE TABLE `tbl_locations` (
  `location_id` int(11) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `floor_no` varchar(255) DEFAULT NULL,
  `room_no` varchar(255) DEFAULT NULL,
  `rack_no` varchar(255) DEFAULT NULL,
  `shelf_no` varchar(255) DEFAULT NULL,
  `bin_no` varchar(255) DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `location_no` bigint(20) DEFAULT NULL,
  `location_remarks` text DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



-- --------------------------------------------------------

--
-- Table structure for table `tbl_login`
--

CREATE TABLE `tbl_login` (
  `login_id` bigint(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile_no` bigint(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `isActive` enum('1','0') NOT NULL DEFAULT '1',
  `isDeleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_login`
--

INSERT INTO `tbl_login` (`login_id`, `email`, `mobile_no`, `password`, `isActive`, `isDeleted`, `created_date`, `user_id`) VALUES
(1, 'admin@admin.com', 1234567890, '$2y$10$9P8wgw7h2YvryIMjwm1u4ekzNXZroald2rgPbE1KSVlprxnuZs.kS', '1', '0', '2019-03-27 05:12:54', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_manufacturing_order`
--

CREATE TABLE `tbl_manufacturing_order` (
  `manufacturing_order_id` bigint(20) NOT NULL,
  `manufacturing_order_no` varchar(255) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `production_deadline` date DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `location_id` bigint(20) NOT NULL,
  `additional_info` text DEFAULT NULL,
  `manufacturing_order_status_id` int(11) NOT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_manufacturing_order_status`
--

CREATE TABLE `tbl_manufacturing_order_status` (
  `manufacturing_order_status_id` bigint(20) NOT NULL,
  `manufacturing_order_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_manufacturing_product_ingredient`
--

CREATE TABLE `tbl_manufacturing_product_ingredient` (
  `manufacturing_product_ingredient_id` bigint(20) NOT NULL,
  `manufacturing_order_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `product_ingredient_id` bigint(20) NOT NULL,
  `notes` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_manufacturing_product_operation`
--

CREATE TABLE `tbl_manufacturing_product_operation` (
  `manufacturing_product_operation_id` bigint(20) NOT NULL,
  `manufacturing_order_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `product_operation_step` bigint(20) NOT NULL,
  `resource` varchar(255) DEFAULT NULL,
  `planned_hours` time DEFAULT NULL,
  `cost` float(10,2) DEFAULT NULL,
  `manufacturing_order_status_id` int(11) DEFAULT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_material`
--

CREATE TABLE `tbl_material` (
  `material_id` bigint(20) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `supplier_id` bigint(20) DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `unit_id` bigint(20) DEFAULT NULL,
  `status` enum('1','0') DEFAULT '1',
  `is_deleted` enum('0','1') DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_material_variant`
--

CREATE TABLE `tbl_material_variant` (
  `material_variant_id` bigint(20) NOT NULL,
  `material_variant_json` text DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_material_variant_setup`
--

CREATE TABLE `tbl_material_variant_setup` (
  `material_variant_setup_id` bigint(20) NOT NULL,
  `material_id` bigint(20) NOT NULL,
  `variant_option` varchar(255) NOT NULL COMMENT 'E.g. color, size, type',
  `variant_option_value` text DEFAULT NULL COMMENT 'option values seperated by comma',
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_model`
--

CREATE TABLE `tbl_model` (
  `model_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `category_id` bigint(20) NOT NULL,
  `model` varchar(255) DEFAULT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_operations`
--

CREATE TABLE `tbl_operations` (
  `operation_id` int(11) NOT NULL,
  `operation_title` varchar(255) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_operations`
--

INSERT INTO `tbl_operations` (`operation_id`, `operation_title`, `status`, `is_deleted`, `created_by`, `created_date`) VALUES
(12, 'cutting', '1', '0', 1, '2024-01-23 11:04:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_package_type`
--

CREATE TABLE `tbl_package_type` (
  `package_type_id` int(11) NOT NULL,
  `package_type` varchar(255) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_package_type`
--

INSERT INTO `tbl_package_type` (`package_type_id`, `package_type`, `status`, `is_deleted`, `created_by`, `created_date`) VALUES
(1, 'Test data', '1', '0', 1, '2024-02-08 16:20:14'),
(2, 'Test data 2', '1', '0', 1, '2024-02-08 16:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pdf_size`
--

CREATE TABLE `tbl_pdf_size` (
  `pdf_size_id` bigint(20) NOT NULL,
  `label_name` varchar(255) DEFAULT NULL,
  `pdf_width` bigint(20) NOT NULL,
  `pdf_height` bigint(20) NOT NULL,
  `page_orientation` varchar(5) DEFAULT NULL,
  `label_count` int(11) DEFAULT NULL,
  `print_for` varchar(255) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_pdf_size`
--

INSERT INTO `tbl_pdf_size` (`pdf_size_id`, `label_name`, `pdf_width`, `pdf_height`, `page_orientation`, `label_count`, `print_for`, `status`, `is_deleted`, `created_by`, `created_date`) VALUES
(12, '76*38mm 1up chromo labels', 76, 38, 'L', 1, 'location', '1', '0', 1, '2024-02-01 15:21:10');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pick_list`
--

CREATE TABLE `tbl_pick_list` (
  `pick_list_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `sales_order_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `fg_id` bigint(20) DEFAULT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  `grn_type` varchar(255) DEFAULT NULL,
  `sfg_box_detail_id` bigint(20) NOT NULL DEFAULT 0,
  `no_of_items` int(11) DEFAULT NULL,
  `required_qty` bigint(20) DEFAULT NULL,
  `no_of_stickers` bigint(20) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `is_general_issue` set('1','0') NOT NULL DEFAULT '0' COMMENT '0 => From Sales Order, 1 => From General Issue',
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `product_id` bigint(20) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_source` varchar(255) DEFAULT NULL,
  `oem_id` bigint(20) DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `quality` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `hsn_code` varchar(50) DEFAULT NULL,
  `article_code` varchar(255) DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `unit_id` bigint(20) DEFAULT NULL,
  `status` enum('1','0') DEFAULT '1',
  `is_deleted` enum('0','1') DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_production_operations`
--

CREATE TABLE `tbl_production_operations` (
  `production_operation_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `operation_id` int(11) DEFAULT NULL,
  `resource_id` bigint(20) DEFAULT NULL,
  `cost_per_hour` float(10,2) DEFAULT NULL,
  `time_taken` time DEFAULT NULL,
  `varient_json` text DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `create_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_alias`
--

CREATE TABLE `tbl_product_alias` (
  `product_alias_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `fg_id` bigint(20) DEFAULT NULL,
  `supplier_id` bigint(20) DEFAULT NULL,
  `product_alias_name` varchar(255) DEFAULT NULL,
  `fg_prod_name` varchar(255) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_grn`
--

CREATE TABLE `tbl_product_grn` (
  `product_grn_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `product_grn_no` varchar(255) DEFAULT NULL,
  `grn_type_id` bigint(20) DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `is_quality_checked` enum('1','0') NOT NULL DEFAULT '0' COMMENT '0 => Not Checked, 1 => Checked',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_grn_detail`
--

CREATE TABLE `tbl_product_grn_detail` (
  `product_grn_detail_id` bigint(20) NOT NULL,
  `product_grn_id` bigint(20) DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `bill_type` varchar(255) DEFAULT NULL,
  `bill_no` varchar(255) DEFAULT NULL,
  `po_date` date DEFAULT NULL,
  `supplier_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `part_code` varchar(255) DEFAULT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `packing` bigint(20) NOT NULL,
  `mfg_date` varchar(255) DEFAULT NULL,
  `expiry_date` varchar(255) DEFAULT NULL,
  `no_of_boxes` bigint(20) DEFAULT NULL,
  `no_of_items` bigint(20) DEFAULT NULL,
  `quality_checked_item` int(11) DEFAULT NULL,
  `purchase_price_per_item` float(10,2) DEFAULT NULL,
  `status` enum('1','0') DEFAULT '1',
  `is_deleted` enum('1','0') DEFAULT '0',
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_ingredients`
--

CREATE TABLE `tbl_product_ingredients` (
  `product_ingredient_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `ingredient_id` bigint(20) NOT NULL COMMENT 'Product id or material id',
  `ingredient_variant_json` text DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `ingredient_notes` text DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_old`
--

CREATE TABLE `tbl_product_old` (
  `product_id` bigint(20) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_source` varchar(255) DEFAULT NULL,
  `oem_id` bigint(20) DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `quality` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `hsn_code` varchar(50) DEFAULT NULL,
  `article_code` varchar(255) DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `unit_id` bigint(20) DEFAULT NULL,
  `status` enum('1','0') DEFAULT '1',
  `is_deleted` enum('0','1') DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_variant`
--

CREATE TABLE `tbl_product_variant` (
  `product_variant_id` bigint(20) NOT NULL,
  `product_variant_title` varchar(255) DEFAULT NULL,
  `product_variant_option` text DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_variant_setup`
--

CREATE TABLE `tbl_product_variant_setup` (
  `product_variant_setup_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `variant_option` varchar(255) NOT NULL COMMENT 'E.g. color, size, type',
  `variant_option_value` text DEFAULT NULL COMMENT 'option values seperated by comma',
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_order`
--

CREATE TABLE `tbl_purchase_order` (
  `purchase_order_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `purchase_order_no` varchar(255) NOT NULL,
  `po_date` date DEFAULT NULL,
  `customer_id` bigint(20) NOT NULL,
  `billing_address` text DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `shipping_description` text DEFAULT NULL,
  `shipping_cost` float(10,2) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL COMMENT 'tax_id',
  `additional_info` text DEFAULT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_order_detail`
--

CREATE TABLE `tbl_purchase_order_detail` (
  `purchase_order_detail_id` bigint(20) NOT NULL,
  `purchase_order_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `product_alias` varchar(255) DEFAULT NULL,
  `quantity` bigint(20) DEFAULT NULL,
  `price_per_unit` float(10,2) DEFAULT NULL,
  `discount_percentage` float(10,2) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quality`
--

CREATE TABLE `tbl_quality` (
  `quality_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `category_id` bigint(20) NOT NULL,
  `quality` varchar(255) DEFAULT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quotes`
--

CREATE TABLE `tbl_quotes` (
  `quotes_id` bigint(20) NOT NULL,
  `quotes_no` varchar(255) NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `customer_reference_no` varchar(255) DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `ship_from` int(11) DEFAULT NULL COMMENT 'Location Id',
  `shipping_description` text DEFAULT NULL,
  `shipping_cost` float(10,2) DEFAULT NULL,
  `shipping_tax_id` int(11) DEFAULT NULL COMMENT 'tax_id',
  `delivery_date` date DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `quote_status` enum('Pending','Confirmed') NOT NULL DEFAULT 'Pending',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quote_product`
--

CREATE TABLE `tbl_quote_product` (
  `quote_product_id` int(11) NOT NULL,
  `quote_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `quantity` bigint(20) DEFAULT NULL,
  `price_per_unit` float(10,2) DEFAULT NULL,
  `discount_percentage` float(10,2) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_raw_material`
--

CREATE TABLE `tbl_raw_material` (
  `raw_material_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `raw_material_name` varchar(255) NOT NULL,
  `raw_material_code` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `subcategory_id` bigint(20) DEFAULT NULL,
  `unit_id` bigint(20) DEFAULT NULL,
  `sustainability_score` varchar(255) DEFAULT NULL,
  `weight` float(10,2) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `hsn_code` varchar(50) DEFAULT NULL,
  `min_level` bigint(20) DEFAULT NULL,
  `max_level` bigint(20) DEFAULT NULL,
  `inward_unit_id` bigint(20) DEFAULT NULL,
  `outward_unit_id` bigint(20) DEFAULT NULL,
  `wastage` bigint(20) DEFAULT NULL,
  `additional_info` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
-- --------------------------------------------------------

--
-- Table structure for table `tbl_rm_box_detail`
--

CREATE TABLE `tbl_rm_box_detail` (
  `rm_box_detail_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `rm_id` bigint(20) DEFAULT NULL,
  `rm_grn_id` bigint(20) DEFAULT NULL,
  `rm_grn_detail_id` bigint(20) DEFAULT NULL,
  `box_name` varchar(255) DEFAULT NULL,
  `box_no` varchar(255) DEFAULT NULL,
  `no_of_items` int(11) DEFAULT NULL,
  `remaining_item` int(11) NOT NULL,
  `is_put_away` enum('1','0') NOT NULL DEFAULT '0' COMMENT '	0 => Put away pending, 1 => Put away done',
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rm_box_location`
--

CREATE TABLE `tbl_rm_box_location` (
  `rm_box_location_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `location_no` bigint(20) DEFAULT NULL,
  `box_no` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rm_grn`
--

CREATE TABLE `tbl_rm_grn` (
  `rm_grn_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `rm_grn_no` varchar(255) DEFAULT NULL,
  `po_no` varchar(255) DEFAULT NULL,
  `po_date` date NOT NULL,
  `supplier_id` bigint(20) NOT NULL,
  `is_rm_quality_checked` enum('0','1') NOT NULL DEFAULT '0' COMMENT '	0 => Not Checked, 1 => Checked',
  `additional_info` text DEFAULT NULL,
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rm_grn_detail`
--

CREATE TABLE `tbl_rm_grn_detail` (
  `rm_grn_detail_id` bigint(20) NOT NULL,
  `rm_grn_id` bigint(20) DEFAULT NULL,
  `rm_id` bigint(20) DEFAULT NULL,
  `hsn_code` varchar(255) DEFAULT NULL,
  `quantity` bigint(20) DEFAULT NULL,
  `inward_unit_id` varchar(255) DEFAULT NULL,
  `rate` float(10,2) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `tax_id` bigint(20) DEFAULT NULL,
  `after_tax` float(10,2) DEFAULT NULL,
  `no_of_boxes` bigint(20) DEFAULT NULL,
  `rm_quality_checked_item` bigint(20) DEFAULT NULL,
  `rm_quality_checked_boxes` bigint(20) DEFAULT NULL,
  `purchase_price_per_item` float(10,2) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales_order`
--

CREATE TABLE `tbl_sales_order` (
  `sales_order_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `sales_order_no` varchar(255) NOT NULL,
  `sales_type_id` bigint(20) NOT NULL,
  `purchase_order_id` varchar(255) DEFAULT NULL,
  `po_date` date DEFAULT NULL,
  `customer_id` bigint(20) NOT NULL,
  `customer_reference_no` varchar(255) DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `shipping_description` text DEFAULT NULL,
  `shipping_cost` float(10,2) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL COMMENT 'tax_id',
  `order_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `sales_order_status_id` int(11) NOT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales_order_delivery_status`
--

CREATE TABLE `tbl_sales_order_delivery_status` (
  `id` int(11) NOT NULL,
  `sales_order_status` varchar(255) NOT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales_order_detail`
--

CREATE TABLE `tbl_sales_order_detail` (
  `sales_order_detail_id` bigint(20) NOT NULL,
  `sales_order_id` bigint(20) DEFAULT NULL,
  `sales_type_id` bigint(20) NOT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `product_alias` varchar(255) DEFAULT NULL,
  `quantity` bigint(20) DEFAULT NULL,
  `price_per_unit` float(10,2) DEFAULT NULL,
  `discount_percentage` float(10,2) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales_type`
--

CREATE TABLE `tbl_sales_type` (
  `sales_type_id` bigint(20) NOT NULL,
  `sales_type_name` varchar(255) DEFAULT NULL,
  `client_id` bigint(20) DEFAULT NULL,
  `is_deleted` enum('0','1') NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_scrap_list`
--

CREATE TABLE `tbl_scrap_list` (
  `scrap_id` bigint(25) NOT NULL,
  `scrap_box_no` bigint(20) NOT NULL,
  `scrap_qty` bigint(20) NOT NULL,
  `remaining_item` bigint(20) NOT NULL,
  `scrap_remaning_items` bigint(20) NOT NULL,
  `location_id` bigint(20) DEFAULT NULL,
  `remark` varchar(255) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `settings_id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_no` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `currency_code_id` int(11) DEFAULT NULL,
  `default_delivery_time_sales_order` int(11) DEFAULT NULL,
  `default_lead_time_purchase_order` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`settings_id`, `company_name`, `address`, `contact_no`, `email`, `logo`, `currency_code_id`, `default_delivery_time_sales_order`, `default_lead_time_purchase_order`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, 1, 14, 14);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sfg`
--

CREATE TABLE `tbl_sfg` (
  `sfg_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `subcategory_id` bigint(20) DEFAULT NULL,
  `unit_id` bigint(20) DEFAULT NULL,
  `sustainability_score` varchar(255) DEFAULT NULL,
  `weight` float(10,2) DEFAULT NULL,
  `sfg_code` varchar(255) DEFAULT NULL,
  `sfg_name` varchar(255) DEFAULT NULL,
  `hsn_code` bigint(20) DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


--
-- Table structure for table `tbl_sfg_box_detail`
--

CREATE TABLE `tbl_sfg_box_detail` (
  `sfg_box_detail_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `sfg_id` bigint(20) DEFAULT NULL,
  `grn_type` varchar(255) DEFAULT NULL,
  `sfg_grn_id` bigint(20) DEFAULT NULL,
  `sfg_grn_detail_id` bigint(20) DEFAULT NULL,
  `box_name` varchar(255) DEFAULT NULL,
  `box_no` varchar(255) DEFAULT NULL,
  `no_of_items` int(11) DEFAULT NULL,
  `remaining_item` int(11) DEFAULT NULL,
  `is_put_away` enum('1','0') NOT NULL DEFAULT '0' COMMENT '	0 => Put away pending, 1 => Put away done',
  `created_by` int(11) DEFAULT NULL,
  `is_dispatch` enum('0','1') NOT NULL DEFAULT '0',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


-- --------------------------------------------------------

--
-- Table structure for table `tbl_sfg_box_location`
--

CREATE TABLE `tbl_sfg_box_location` (
  `sfg_box_location_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `location_no` bigint(20) DEFAULT NULL,
  `grn_type` varchar(255) DEFAULT NULL,
  `box_no` bigint(20) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


-- --------------------------------------------------------

--
-- Table structure for table `tbl_sfg_grn`
--

CREATE TABLE `tbl_sfg_grn` (
  `sfg_grn_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `sfg_grn_no` varchar(255) NOT NULL,
  `grn_type` varchar(255) DEFAULT NULL,
  `po_no` varchar(255) DEFAULT NULL,
  `po_date` date DEFAULT NULL,
  `is_sfg_quality_checked` enum('0','1') NOT NULL DEFAULT '0',
  `additional_info` text NOT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


--
-- Table structure for table `tbl_sfg_grn_detail`
--

CREATE TABLE `tbl_sfg_grn_detail` (
  `sfg_grn_detail_id` bigint(20) NOT NULL,
  `sfg_grn_id` bigint(20) DEFAULT NULL,
  `sfg_id` bigint(20) DEFAULT NULL,
  `supplier_id` bigint(20) DEFAULT NULL,
  `hsn_code` varchar(255) DEFAULT NULL,
  `quantity` bigint(20) DEFAULT NULL,
  `inward_unit_id` varchar(255) DEFAULT NULL,
  `rate` float(10,2) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `tax_id` bigint(20) DEFAULT NULL,
  `after_tax` float(10,2) DEFAULT NULL,
  `no_of_boxes` bigint(20) DEFAULT NULL,
  `sfg_quality_checked_item` bigint(20) DEFAULT NULL,
  `sfg_quality_checked_boxes` bigint(20) DEFAULT NULL,
  `purchase_price_per_item` float(10,2) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


-- --------------------------------------------------------

--
-- Table structure for table `tbl_site_info`
--

CREATE TABLE `tbl_site_info` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone_no` varchar(255) NOT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_size`
--

CREATE TABLE `tbl_size` (
  `size_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `category_id` bigint(20) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_state`
--

CREATE TABLE `tbl_state` (
  `state_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(128) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tbl_state`
--

INSERT INTO `tbl_state` (`state_id`, `country_id`, `code`, `name`, `status`) VALUES
(28, 1, 'AN', 'Andaman and Nicobar Islands', 1),
(29, 1, 'AP', 'Andhra Pradesh', 1),
(30, 1, 'AR', 'Arunachal Pradesh', 1),
(31, 1, 'AS', 'Assam', 1),
(32, 1, 'BI', 'Bihar', 1),
(33, 1, 'CH', 'Chandigarh', 1),
(34, 1, 'DA', 'Dadra and Nagar Haveli', 1),
(35, 1, 'DM', 'Daman and Diu', 1),
(36, 1, 'DE', 'Delhi', 1),
(37, 1, 'GO', 'Goa', 1),
(38, 1, 'GU', 'Gujarat', 1),
(39, 1, 'HA', 'Haryana', 1),
(40, 1, 'HP', 'Himachal Pradesh', 1),
(41, 1, 'JA', 'Jammu and Kashmir', 1),
(42, 1, 'KA', 'Karnataka', 1),
(43, 1, 'KE', 'Kerala', 1),
(44, 1, 'LI', 'Lakshadweep Islands', 1),
(45, 1, 'MP', 'Madhya Pradesh', 1),
(46, 1, 'MA', 'Maharashtra', 1),
(47, 1, 'MN', 'Manipur', 1),
(48, 1, 'ME', 'Meghalaya', 1),
(49, 1, 'MI', 'Mizoram', 1),
(50, 1, 'NA', 'Nagaland', 1),
(51, 1, 'OR', 'Orissa', 1),
(52, 1, 'PO', 'Pondicherry', 1),
(53, 1, 'PU', 'Punjab', 1),
(54, 1, 'RA', 'Rajasthan', 1),
(55, 1, 'SI', 'Sikkim', 1),
(56, 1, 'TN', 'Tamil Nadu', 1),
(57, 1, 'TR', 'Tripura', 1),
(58, 1, 'UP', 'Uttar Pradesh', 1),
(59, 1, 'WB', 'West Bengal', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_audit`
--

CREATE TABLE `tbl_stock_audit` (
  `stock_audit_id` bigint(20) NOT NULL,
  `location_no` bigint(20) DEFAULT NULL,
  `box_no` varchar(255) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_by` bigint(20) NOT NULL DEFAULT 1,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


-- --------------------------------------------------------

--
-- Table structure for table `tbl_stores`
--

CREATE TABLE `tbl_stores` (
  `store_id` bigint(20) NOT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_stores`
--

INSERT INTO `tbl_stores` (`store_id`, `branch_id`, `store_name`, `status`, `is_deleted`, `created_by`, `created_date`) VALUES
(18, 7, 'House of sharma', '1', '0', 1, '2025-09-18 17:37:16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_store_detail`
--

CREATE TABLE `tbl_store_detail` (
  `store_detail_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `no_of_floor` int(11) NOT NULL,
  `no_of_room` int(11) NOT NULL,
  `no_of_rack` int(11) NOT NULL,
  `no_of_shelf` int(11) NOT NULL,
  `no_of_bin` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_store_detail`
--

INSERT INTO `tbl_store_detail` (`store_detail_id`, `store_id`, `no_of_floor`, `no_of_room`, `no_of_rack`, `no_of_shelf`, `no_of_bin`, `status`, `is_deleted`, `created_date`) VALUES
(1, NULL, 10, 20, 40, 20, 20, '1', '0', '2024-02-08 18:36:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subcategory`
--

CREATE TABLE `tbl_subcategory` (
  `subcategory_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `category_id` bigint(20) NOT NULL,
  `subcategory` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `is_delete` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplier`
--

CREATE TABLE `tbl_supplier` (
  `supplier_id` bigint(20) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `mobile_no` bigint(20) DEFAULT NULL,
  `pan_no` varchar(50) DEFAULT NULL,
  `gst_no` varchar(100) DEFAULT NULL,
  `additional_comments` text DEFAULT NULL,
  `is_oem` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `register_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_supplier`
--

INSERT INTO `tbl_supplier` (`supplier_id`, `company_name`, `email`, `address`, `mobile_no`, `pan_no`, `gst_no`, `additional_comments`, `is_oem`, `created_by`, `status`, `is_deleted`, `register_date`) VALUES
(1, 'test', '', '', 7867865872, '', '', '<div><br></div>', '0', 1, '1', '0', '2025-09-26 10:48:58');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tax`
--

CREATE TABLE `tbl_tax` (
  `tax_id` int(11) NOT NULL,
  `tax_name` varchar(255) DEFAULT NULL,
  `tax_rate` float(10,2) DEFAULT 0.00,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_trolley_type`
--

CREATE TABLE `tbl_trolley_type` (
  `trolley_type_id` bigint(20) NOT NULL,
  `trolley_type` varchar(255) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unit_conversion`
--

CREATE TABLE `tbl_unit_conversion` (
  `unit_conversion_id` bigint(20) NOT NULL,
  `client_id` bigint(20) DEFAULT NULL,
  `from_unit_id` bigint(20) NOT NULL,
  `from_unit_value` float(10,2) DEFAULT NULL,
  `to_unit_id` bigint(20) NOT NULL,
  `to_unit_value` float(10,2) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unit_of_measure`
--

CREATE TABLE `tbl_unit_of_measure` (
  `unit_id` int(11) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `unit` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_unit_of_measure`
--

INSERT INTO `tbl_unit_of_measure` (`unit_id`, `store_id`, `unit`, `created_by`, `status`, `is_deleted`, `created_date`) VALUES
(1, 18, 'Kgs', 1, '1', '0', '2025-09-24 10:06:59');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` bigint(20) NOT NULL,
  `client_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `mobile_no` bigint(20) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `identity_no` varchar(255) DEFAULT NULL,
  `user_type_id` varchar(100) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `register_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `client_id`, `name`, `email`, `address`, `mobile_no`, `city`, `dob`, `identity_no`, `user_type_id`, `created_by`, `status`, `is_deleted`, `register_date`) VALUES
(1, 1, 'Admin', 'admin@admin.com', '', 7439217113, NULL, NULL, NULL, '1', NULL, '1', '0', '2015-05-06 11:21:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_permission`
--

CREATE TABLE `tbl_user_permission` (
  `user_permission_id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `user_type_id` bigint(20) DEFAULT NULL,
  `admin_module_id` int(11) DEFAULT NULL,
  `view_permission` int(11) DEFAULT NULL,
  `delete_permission` int(11) DEFAULT NULL,
  `create_permission` int(11) DEFAULT NULL,
  `update_permission` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) DEFAULT NULL,
  `status` enum('0','1') DEFAULT '1',
  `modified_by` bigint(20) DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_user_permission`
--

INSERT INTO `tbl_user_permission` (`user_permission_id`, `user_id`, `user_type_id`, `admin_module_id`, `view_permission`, `delete_permission`, `create_permission`, `update_permission`, `created_date`, `created_by`, `status`, `modified_by`, `modified_date`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, '2024-01-12 03:07:25', NULL, '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_type`
--

CREATE TABLE `tbl_user_type` (
  `user_type_id` int(11) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `isdeleted` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `is_visible` enum('1','0') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_user_type`
--

INSERT INTO `tbl_user_type` (`user_type_id`, `user_type`, `isdeleted`, `status`, `is_visible`) VALUES
(1, 'Admin', '0', '1', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_variant`
--

CREATE TABLE `tbl_variant` (
  `variant_id` bigint(20) NOT NULL,
  `variant_title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `variant_option` text DEFAULT NULL,
  `variant_required` enum('Yes','No') NOT NULL DEFAULT 'No',
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_variant`
--

INSERT INTO `tbl_variant` (`variant_id`, `variant_title`, `slug`, `variant_option`, `variant_required`, `status`, `is_deleted`, `created_by`, `created_date`, `order_by`) VALUES
(1, 'Model', 'model', 'AXR1, AXR7+,AWR1,TDM230,1 UP,2 UP, King Blue, King, Sartan', 'No', '1', '0', 1, '2024-02-28 07:21:47', NULL),
(2, 'Size', 'size', '55x300, 55x450,110x450,110x300,100x150,100x50,75x50,50x25,25x225,32x225,38x225', 'No', '1', '0', 1, '2024-02-28 07:23:09', NULL),
(3, 'Material/Quality', 'materialquality', 'Wax, Wax Raising APRE, Wax Raising APRE600,Poly,Chromo, D.T.', 'No', '1', '0', 1, '2024-02-28 07:50:59', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_admin_module`
--
ALTER TABLE `tbl_admin_module`
  ADD PRIMARY KEY (`admin_module_id`);

--
-- Indexes for table `tbl_bom`
--
ALTER TABLE `tbl_bom`
  ADD PRIMARY KEY (`bom_id`);

--
-- Indexes for table `tbl_box_detail`
--
ALTER TABLE `tbl_box_detail`
  ADD PRIMARY KEY (`box_detail_id`);

--
-- Indexes for table `tbl_box_detail_old`
--
ALTER TABLE `tbl_box_detail_old`
  ADD PRIMARY KEY (`box_detail_id`);

--
-- Indexes for table `tbl_box_location`
--
ALTER TABLE `tbl_box_location`
  ADD PRIMARY KEY (`box_location_id`);

--
-- Indexes for table `tbl_branch`
--
ALTER TABLE `tbl_branch`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_client`
--
ALTER TABLE `tbl_client`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `tbl_contacts`
--
ALTER TABLE `tbl_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_country`
--
ALTER TABLE `tbl_country`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `tbl_coupon_code`
--
ALTER TABLE `tbl_coupon_code`
  ADD PRIMARY KEY (`coupon_code_id`);

--
-- Indexes for table `tbl_currency`
--
ALTER TABLE `tbl_currency`
  ADD PRIMARY KEY (`currency_id`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `tbl_default_tax_rate`
--
ALTER TABLE `tbl_default_tax_rate`
  ADD PRIMARY KEY (`default_tax_id`);

--
-- Indexes for table `tbl_department`
--
ALTER TABLE `tbl_department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `tbl_fg`
--
ALTER TABLE `tbl_fg`
  ADD PRIMARY KEY (`fg_id`);

--
-- Indexes for table `tbl_general_issues`
--
ALTER TABLE `tbl_general_issues`
  ADD PRIMARY KEY (`general_issues_id`);

--
-- Indexes for table `tbl_grn_type`
--
ALTER TABLE `tbl_grn_type`
  ADD PRIMARY KEY (`grn_type_id`);

--
-- Indexes for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `tbl_locations`
--
ALTER TABLE `tbl_locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD PRIMARY KEY (`login_id`);

--
-- Indexes for table `tbl_manufacturing_order`
--
ALTER TABLE `tbl_manufacturing_order`
  ADD PRIMARY KEY (`manufacturing_order_id`);

--
-- Indexes for table `tbl_manufacturing_order_status`
--
ALTER TABLE `tbl_manufacturing_order_status`
  ADD PRIMARY KEY (`manufacturing_order_status_id`);

--
-- Indexes for table `tbl_manufacturing_product_ingredient`
--
ALTER TABLE `tbl_manufacturing_product_ingredient`
  ADD PRIMARY KEY (`manufacturing_product_ingredient_id`);

--
-- Indexes for table `tbl_manufacturing_product_operation`
--
ALTER TABLE `tbl_manufacturing_product_operation`
  ADD PRIMARY KEY (`manufacturing_product_operation_id`);

--
-- Indexes for table `tbl_material`
--
ALTER TABLE `tbl_material`
  ADD PRIMARY KEY (`material_id`);

--
-- Indexes for table `tbl_material_variant`
--
ALTER TABLE `tbl_material_variant`
  ADD PRIMARY KEY (`material_variant_id`);

--
-- Indexes for table `tbl_material_variant_setup`
--
ALTER TABLE `tbl_material_variant_setup`
  ADD PRIMARY KEY (`material_variant_setup_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `tbl_model`
--
ALTER TABLE `tbl_model`
  ADD PRIMARY KEY (`model_id`);

--
-- Indexes for table `tbl_operations`
--
ALTER TABLE `tbl_operations`
  ADD PRIMARY KEY (`operation_id`);

--
-- Indexes for table `tbl_package_type`
--
ALTER TABLE `tbl_package_type`
  ADD PRIMARY KEY (`package_type_id`);

--
-- Indexes for table `tbl_pdf_size`
--
ALTER TABLE `tbl_pdf_size`
  ADD PRIMARY KEY (`pdf_size_id`);

--
-- Indexes for table `tbl_pick_list`
--
ALTER TABLE `tbl_pick_list`
  ADD PRIMARY KEY (`pick_list_id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tbl_production_operations`
--
ALTER TABLE `tbl_production_operations`
  ADD PRIMARY KEY (`production_operation_id`);

--
-- Indexes for table `tbl_product_alias`
--
ALTER TABLE `tbl_product_alias`
  ADD PRIMARY KEY (`product_alias_id`);

--
-- Indexes for table `tbl_product_grn`
--
ALTER TABLE `tbl_product_grn`
  ADD PRIMARY KEY (`product_grn_id`);

--
-- Indexes for table `tbl_product_grn_detail`
--
ALTER TABLE `tbl_product_grn_detail`
  ADD PRIMARY KEY (`product_grn_detail_id`);

--
-- Indexes for table `tbl_product_ingredients`
--
ALTER TABLE `tbl_product_ingredients`
  ADD PRIMARY KEY (`product_ingredient_id`);

--
-- Indexes for table `tbl_product_old`
--
ALTER TABLE `tbl_product_old`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tbl_product_variant`
--
ALTER TABLE `tbl_product_variant`
  ADD PRIMARY KEY (`product_variant_id`);

--
-- Indexes for table `tbl_product_variant_setup`
--
ALTER TABLE `tbl_product_variant_setup`
  ADD PRIMARY KEY (`product_variant_setup_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `tbl_purchase_order`
--
ALTER TABLE `tbl_purchase_order`
  ADD PRIMARY KEY (`purchase_order_id`);

--
-- Indexes for table `tbl_purchase_order_detail`
--
ALTER TABLE `tbl_purchase_order_detail`
  ADD PRIMARY KEY (`purchase_order_detail_id`);

--
-- Indexes for table `tbl_quality`
--
ALTER TABLE `tbl_quality`
  ADD PRIMARY KEY (`quality_id`);

--
-- Indexes for table `tbl_quotes`
--
ALTER TABLE `tbl_quotes`
  ADD PRIMARY KEY (`quotes_id`);

--
-- Indexes for table `tbl_quote_product`
--
ALTER TABLE `tbl_quote_product`
  ADD PRIMARY KEY (`quote_product_id`);

--
-- Indexes for table `tbl_raw_material`
--
ALTER TABLE `tbl_raw_material`
  ADD PRIMARY KEY (`raw_material_id`);

--
-- Indexes for table `tbl_rm_box_detail`
--
ALTER TABLE `tbl_rm_box_detail`
  ADD PRIMARY KEY (`rm_box_detail_id`);

--
-- Indexes for table `tbl_rm_box_location`
--
ALTER TABLE `tbl_rm_box_location`
  ADD PRIMARY KEY (`rm_box_location_id`);

--
-- Indexes for table `tbl_rm_grn`
--
ALTER TABLE `tbl_rm_grn`
  ADD PRIMARY KEY (`rm_grn_id`);

--
-- Indexes for table `tbl_rm_grn_detail`
--
ALTER TABLE `tbl_rm_grn_detail`
  ADD PRIMARY KEY (`rm_grn_detail_id`);

--
-- Indexes for table `tbl_sales_order`
--
ALTER TABLE `tbl_sales_order`
  ADD PRIMARY KEY (`sales_order_id`);

--
-- Indexes for table `tbl_sales_order_delivery_status`
--
ALTER TABLE `tbl_sales_order_delivery_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sales_order_detail`
--
ALTER TABLE `tbl_sales_order_detail`
  ADD PRIMARY KEY (`sales_order_detail_id`);

--
-- Indexes for table `tbl_sales_type`
--
ALTER TABLE `tbl_sales_type`
  ADD PRIMARY KEY (`sales_type_id`);

--
-- Indexes for table `tbl_scrap_list`
--
ALTER TABLE `tbl_scrap_list`
  ADD PRIMARY KEY (`scrap_id`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`settings_id`);

--
-- Indexes for table `tbl_sfg`
--
ALTER TABLE `tbl_sfg`
  ADD PRIMARY KEY (`sfg_id`);

--
-- Indexes for table `tbl_sfg_box_detail`
--
ALTER TABLE `tbl_sfg_box_detail`
  ADD PRIMARY KEY (`sfg_box_detail_id`);

--
-- Indexes for table `tbl_sfg_box_location`
--
ALTER TABLE `tbl_sfg_box_location`
  ADD PRIMARY KEY (`sfg_box_location_id`);

--
-- Indexes for table `tbl_sfg_grn`
--
ALTER TABLE `tbl_sfg_grn`
  ADD PRIMARY KEY (`sfg_grn_id`);

--
-- Indexes for table `tbl_sfg_grn_detail`
--
ALTER TABLE `tbl_sfg_grn_detail`
  ADD PRIMARY KEY (`sfg_grn_detail_id`);

--
-- Indexes for table `tbl_site_info`
--
ALTER TABLE `tbl_site_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_size`
--
ALTER TABLE `tbl_size`
  ADD PRIMARY KEY (`size_id`);

--
-- Indexes for table `tbl_state`
--
ALTER TABLE `tbl_state`
  ADD PRIMARY KEY (`state_id`);

--
-- Indexes for table `tbl_stock_audit`
--
ALTER TABLE `tbl_stock_audit`
  ADD PRIMARY KEY (`stock_audit_id`);

--
-- Indexes for table `tbl_stores`
--
ALTER TABLE `tbl_stores`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `tbl_store_detail`
--
ALTER TABLE `tbl_store_detail`
  ADD PRIMARY KEY (`store_detail_id`);

--
-- Indexes for table `tbl_subcategory`
--
ALTER TABLE `tbl_subcategory`
  ADD PRIMARY KEY (`subcategory_id`);

--
-- Indexes for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `tbl_tax`
--
ALTER TABLE `tbl_tax`
  ADD PRIMARY KEY (`tax_id`);

--
-- Indexes for table `tbl_trolley_type`
--
ALTER TABLE `tbl_trolley_type`
  ADD PRIMARY KEY (`trolley_type_id`);

--
-- Indexes for table `tbl_unit_conversion`
--
ALTER TABLE `tbl_unit_conversion`
  ADD PRIMARY KEY (`unit_conversion_id`);

--
-- Indexes for table `tbl_unit_of_measure`
--
ALTER TABLE `tbl_unit_of_measure`
  ADD PRIMARY KEY (`unit_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_user_permission`
--
ALTER TABLE `tbl_user_permission`
  ADD PRIMARY KEY (`user_permission_id`);

--
-- Indexes for table `tbl_user_type`
--
ALTER TABLE `tbl_user_type`
  ADD PRIMARY KEY (`user_type_id`);

--
-- Indexes for table `tbl_variant`
--
ALTER TABLE `tbl_variant`
  ADD PRIMARY KEY (`variant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_admin_module`
--
ALTER TABLE `tbl_admin_module`
  MODIFY `admin_module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_bom`
--
ALTER TABLE `tbl_bom`
  MODIFY `bom_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_box_detail`
--
ALTER TABLE `tbl_box_detail`
  MODIFY `box_detail_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_box_detail_old`
--
ALTER TABLE `tbl_box_detail_old`
  MODIFY `box_detail_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_box_location`
--
ALTER TABLE `tbl_box_location`
  MODIFY `box_location_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_branch`
--
ALTER TABLE `tbl_branch`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `category_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_client`
--
ALTER TABLE `tbl_client`
  MODIFY `client_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_contacts`
--
ALTER TABLE `tbl_contacts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_country`
--
ALTER TABLE `tbl_country`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_coupon_code`
--
ALTER TABLE `tbl_coupon_code`
  MODIFY `coupon_code_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_currency`
--
ALTER TABLE `tbl_currency`
  MODIFY `currency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `customer_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_default_tax_rate`
--
ALTER TABLE `tbl_default_tax_rate`
  MODIFY `default_tax_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_department`
--
ALTER TABLE `tbl_department`
  MODIFY `department_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  MODIFY `employee_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_fg`
--
ALTER TABLE `tbl_fg`
  MODIFY `fg_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `tbl_general_issues`
--
ALTER TABLE `tbl_general_issues`
  MODIFY `general_issues_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_grn_type`
--
ALTER TABLE `tbl_grn_type`
  MODIFY `grn_type_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `invoice_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_locations`
--
ALTER TABLE `tbl_locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_login`
--
ALTER TABLE `tbl_login`
  MODIFY `login_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_manufacturing_order`
--
ALTER TABLE `tbl_manufacturing_order`
  MODIFY `manufacturing_order_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_manufacturing_order_status`
--
ALTER TABLE `tbl_manufacturing_order_status`
  MODIFY `manufacturing_order_status_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_manufacturing_product_ingredient`
--
ALTER TABLE `tbl_manufacturing_product_ingredient`
  MODIFY `manufacturing_product_ingredient_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_manufacturing_product_operation`
--
ALTER TABLE `tbl_manufacturing_product_operation`
  MODIFY `manufacturing_product_operation_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_material`
--
ALTER TABLE `tbl_material`
  MODIFY `material_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_material_variant`
--
ALTER TABLE `tbl_material_variant`
  MODIFY `material_variant_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_material_variant_setup`
--
ALTER TABLE `tbl_material_variant_setup`
  MODIFY `material_variant_setup_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_model`
--
ALTER TABLE `tbl_model`
  MODIFY `model_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_operations`
--
ALTER TABLE `tbl_operations`
  MODIFY `operation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_package_type`
--
ALTER TABLE `tbl_package_type`
  MODIFY `package_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_pdf_size`
--
ALTER TABLE `tbl_pdf_size`
  MODIFY `pdf_size_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_pick_list`
--
ALTER TABLE `tbl_pick_list`
  MODIFY `pick_list_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `product_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_production_operations`
--
ALTER TABLE `tbl_production_operations`
  MODIFY `production_operation_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_product_alias`
--
ALTER TABLE `tbl_product_alias`
  MODIFY `product_alias_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_product_grn`
--
ALTER TABLE `tbl_product_grn`
  MODIFY `product_grn_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_product_grn_detail`
--
ALTER TABLE `tbl_product_grn_detail`
  MODIFY `product_grn_detail_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_product_ingredients`
--
ALTER TABLE `tbl_product_ingredients`
  MODIFY `product_ingredient_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_product_old`
--
ALTER TABLE `tbl_product_old`
  MODIFY `product_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_product_variant`
--
ALTER TABLE `tbl_product_variant`
  MODIFY `product_variant_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_product_variant_setup`
--
ALTER TABLE `tbl_product_variant_setup`
  MODIFY `product_variant_setup_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_purchase_order`
--
ALTER TABLE `tbl_purchase_order`
  MODIFY `purchase_order_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_purchase_order_detail`
--
ALTER TABLE `tbl_purchase_order_detail`
  MODIFY `purchase_order_detail_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_quality`
--
ALTER TABLE `tbl_quality`
  MODIFY `quality_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_quotes`
--
ALTER TABLE `tbl_quotes`
  MODIFY `quotes_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_quote_product`
--
ALTER TABLE `tbl_quote_product`
  MODIFY `quote_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_raw_material`
--
ALTER TABLE `tbl_raw_material`
  MODIFY `raw_material_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=295;

--
-- AUTO_INCREMENT for table `tbl_rm_box_detail`
--
ALTER TABLE `tbl_rm_box_detail`
  MODIFY `rm_box_detail_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rm_box_location`
--
ALTER TABLE `tbl_rm_box_location`
  MODIFY `rm_box_location_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rm_grn`
--
ALTER TABLE `tbl_rm_grn`
  MODIFY `rm_grn_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rm_grn_detail`
--
ALTER TABLE `tbl_rm_grn_detail`
  MODIFY `rm_grn_detail_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_sales_order`
--
ALTER TABLE `tbl_sales_order`
  MODIFY `sales_order_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_sales_order_delivery_status`
--
ALTER TABLE `tbl_sales_order_delivery_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_sales_order_detail`
--
ALTER TABLE `tbl_sales_order_detail`
  MODIFY `sales_order_detail_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_sales_type`
--
ALTER TABLE `tbl_sales_type`
  MODIFY `sales_type_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_scrap_list`
--
ALTER TABLE `tbl_scrap_list`
  MODIFY `scrap_id` bigint(25) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_sfg`
--
ALTER TABLE `tbl_sfg`
  MODIFY `sfg_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_sfg_box_detail`
--
ALTER TABLE `tbl_sfg_box_detail`
  MODIFY `sfg_box_detail_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_sfg_box_location`
--
ALTER TABLE `tbl_sfg_box_location`
  MODIFY `sfg_box_location_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_sfg_grn`
--
ALTER TABLE `tbl_sfg_grn`
  MODIFY `sfg_grn_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_sfg_grn_detail`
--
ALTER TABLE `tbl_sfg_grn_detail`
  MODIFY `sfg_grn_detail_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_site_info`
--
ALTER TABLE `tbl_site_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_size`
--
ALTER TABLE `tbl_size`
  MODIFY `size_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_state`
--
ALTER TABLE `tbl_state`
  MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `tbl_stock_audit`
--
ALTER TABLE `tbl_stock_audit`
  MODIFY `stock_audit_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_stores`
--
ALTER TABLE `tbl_stores`
  MODIFY `store_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_store_detail`
--
ALTER TABLE `tbl_store_detail`
  MODIFY `store_detail_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_subcategory`
--
ALTER TABLE `tbl_subcategory`
  MODIFY `subcategory_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  MODIFY `supplier_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_tax`
--
ALTER TABLE `tbl_tax`
  MODIFY `tax_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_trolley_type`
--
ALTER TABLE `tbl_trolley_type`
  MODIFY `trolley_type_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_unit_conversion`
--
ALTER TABLE `tbl_unit_conversion`
  MODIFY `unit_conversion_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_unit_of_measure`
--
ALTER TABLE `tbl_unit_of_measure`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user_permission`
--
ALTER TABLE `tbl_user_permission`
  MODIFY `user_permission_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user_type`
--
ALTER TABLE `tbl_user_type`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_variant`
--
ALTER TABLE `tbl_variant`
  MODIFY `variant_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
