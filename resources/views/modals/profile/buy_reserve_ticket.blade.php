<?php
$auth_user = \Illuminate\Support\Facades\Auth::user();
?>

<div class="modal fade" id="BuyReserveModal" tabindex="-1" role="dialog" aria-labelledby="productModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-body p-4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-dark">&times;</span>
                </button>
                <h3 class="text-center">
                    Анкета покупателя
                </h3>
                <input type="hidden" id="ticket_id" value="">
                <div class="container pt-3">
                    <div class="row justify-content-center">
                        <div class="col-10">
                            <div class="row">
                                <div class="col-6 ">
                                    <p class="mb-0">Ваше имя: <span class="ml-2 font-weight-bold">{{ $auth_user->name }}</span></p>
                                </div>
                                <div class="col-6 ">
                                    <p class="mb-0">Ваша фамилия: <span class="ml-2 font-weight-bold">{{ $auth_user->last_name }}</span></p>
                                </div>
                                <div class="col-6 my-0">
                                    <p class="mb-0">Ваше отчество: <span class="ml-2 font-weight-bold">{{ $auth_user->middle_name }}</span></p>
                                </div>
                                <div class="col-6 my-0">
                                    <p class="mb-0">Ваш email: <span class="ml-2 font-weight-bold">{{ $auth_user->email }}</span></p>
                                </div>
                                <div class="col-6 my-0">
                                    <p class="mb-0">Ваш телефон: <span class="ml-2 font-weight-bold">{{ $auth_user->contacts }}</span></p>
                                </div>
                                <div class="col-6 my-0">
                                    <p class="mb-0">Ваш город: <span class="ml-2 font-weight-bold">{{ $auth_user->city }}</span></p>
                                </div>
                                <div class="col-12 py-3"></div>
                                <div class="col-6 my-0">
                                    <p class="mb-0">Ряд: <span class="ml-2 font-weight-bold row_modal"></span></p>
                                </div>
                                <div class="col-6 my-0">
                                    <p class="mb-0">Место: <span class="ml-2 font-weight-bold collumn_modal"></span></p>
                                </div>
                            </div>
                            <form class="row justify-content-center pt-5" action="">
                                @csrf
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="show_name" id="show_name">
                                        <label class="custom-control-label" for="show_name" style="cursor:pointer; user-select: none;">Отображать ваше имя в схеме зала?</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="online_card" id="online_card">
                                        <label class="custom-control-label" for="online_card" style="cursor:pointer; user-select: none;">Оплата электронной картой</label>
                                    </div>
                                </div>
                                <div class="col-12 pt-3">
                                    <div class="agree_info p-3" style="height:auto; max-height: 200px; overflow-y: auto; border:1px solid rgba(0,0,0,0.25); border-radius:5px;">
                                        <p>
                                            1.1. Настоящее Соглашение заключается между Покупателем и Интернет-магазином в момент оформления заказа. Покупатель подтверждает свое согласие с условиями, установленными настоящим Соглашением, путём установления отметки (галочки) в графе «Я прочитал Условия соглашения и согласен с ними» при оформлении заказа или регистрации на Сайте.

                                            1.2. Настоящие Соглашение, а также информация о товаре, представленная на Сайте, являются публичной офертой в соответствии со ст.435 и ч.2 ст.437 ГК РФ.

                                            1.3. К отношениям между Покупателем и Интернет-магазином применяются положения ГК РФ о розничной купле-продаже (§ 2 глава 30), а также Закон РФ «О защите прав потребителей» от 07.02.1992 № 2300-1 и иные правовые акты, принятые в соответствии с ними.

                                            1.4. Покупателем может быть любое физическое или юридическое лицо, способное принять и оплатить заказанный им товар в порядке и на условиях, установленных настоящим Соглашением.

                                            1.5. Интернет-магазин оставляет за собой право вносить изменения в настоящее Соглашение.

                                            1.6. Настоящее Соглашение должно рассматриваться в том виде, как оно опубликовано на Сайте и должно применяться, и толковаться в соответствии с законодательством Российской Федерации.
                                        </p>
                                    </div>
                                    <div class="custom-control custom-checkbox pt-2">
                                        <input type="checkbox" class="custom-control-input" name="agree" id="agree">
                                        <label class="custom-control-label" for="agree" style="cursor:pointer; user-select: none;">я принимаю условия соглашения</label>
                                    </div>
                                </div>
                                <div class="text-center py-3">
                                    <div class="btn btn-warning buy-ticket agree_check agree_block" style="transition: 0.5s">Отправить</div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>
