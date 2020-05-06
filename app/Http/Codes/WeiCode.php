<?php namespace App\Http\Codes;


class WeiCode{

    const success      = 'success';
	const not_exists = 'not_exists';
	const exists = 'exists';

	//广告
	const create_advert_fail = 'wei_create_advert_fail';
	const get_advert_fail = 'wei_get_advert_fail';
	const update_advert_fail = 'wei_update_advert_fail';
	const delete_advert_fail = 'wei_delete_advert_fail';

    //create fail
    const create_fictitiousCard_fail = 'wei_create_fictitiousCard_fail';
    const create_store_fail = 'wei_create_store_fail';
    const create_exe_fail = 'wei_create_exe_fail';
    const create_exeOprator_fail = 'wei_create_exeOprator_fail';

    //update fail
    const update_exeOprator_fail = 'wei_update_exeOprator_fail';
    const update_store_fail = 'wei_update_store_fail';
    const update_exe_fail = 'wei_update_exe_fail';
	const update_member_fail = 'wei_update_member_fail';

	//get fail
	const get_exe_fail = 'wei_get_exe_fail';
	const get_mch_fail = 'wei_get_mch_fail';
	const get_mchCard_fail = 'wei_get_mchCard_fail';
	const get_store_fail = 'wei_get_store_fail';
	const get_exeOprator_fail = 'wei_get_exeOprator_fail';
	const get_payOrder_fail = 'wei_get_payOrder_fail';
	const get_refundOrder_fail = 'wei_get_refundOrder_fail';

	//delete fail
	const delete_exe_fail = 'wei_delete_exe_fail';
	const delete_exeOprator_fail = 'wei_delete_exeOprator_fail';
	const delete_store_fail = 'wei_delete_store_fail';
}