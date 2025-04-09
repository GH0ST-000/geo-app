import React from 'react';
import './HoneyModuleDetail.css'
import HoneyImage from '../../assets/images/honeyDetail.webp'
import {useTranslation} from "react-i18next";
const HoneyModuleDetail = () => {

    const { t } = useTranslation()
    return (
        <div className='col-11 col-md-9 mx-auto mt-5 mb-5'>
            <div className='honey-image-container'>
                <img src={HoneyImage} alt='honey-image' className='honey-detail-image'/>
            </div>


            <div className='details-info-wrapper d-flex justify-content-between align-items-start w-100 mt-4'>
                <div className='details-text-wrapper col-8'>
                    <div>
                        <h2 className='detail-title'>{t('honeyStandard')}</h2>
                        <p className='detail-text'>შემთხვევითად გენერირებული ტექსტი ეხმარება დიზაინერებს და
                            ტიპოგრაფიული ნაწარმის შემქმნელებს, რეალურთან მაქსიმალურად მიახლოებული შაბლონი წარუდგინონ
                            შემფასებელს.
                            ხშირადაა შემთხვევა, როდესაც დიზაინის შესრულებისას საჩვენებელია, თუ როგორი იქნება ტექსტის
                            ბლოკი. სწორედ ასეთ დროს არის მოსახერხებელი ამ გენერატორით შექმნილი ტექსტის გამოყენება,
                            რადგან უბრალოდ „ტექსტი ტექსტი ტექსტი“ ან სხვა გამეორებადი სიტყვების ჩაყრა, ხელოვნურ ვიზუალურ
                            სიმეტრიას ქმნის და არაბუნებრივად გამოიყურება.
                        </p>
                    </div>


                    <div className='mb-4'>
                        <p className='detail-text'>შემთხვევითად გენერირებული ტექსტი ეხმარება დიზაინერებს და
                            ტიპოგრაფიული ნაწარმის შემქმნელებს, რეალურთან მაქსიმალურად მიახლოებული შაბლონი წარუდგინონ
                            შემფასებელს.
                            ხშირადაა შემთხვევა, როდესაც დიზაინის შესრულებისას საჩვენებელია, თუ როგორი იქნება ტექსტის
                            ბლოკი. სწორედ ასეთ დროს არის მოსახერხებელი ამ გენერატორით შექმნილი ტექსტის გამოყენება,
                            რადგან უბრალოდ „ტექსტი ტექსტი ტექსტი“ ან სხვა გამეორებადი სიტყვების ჩაყრა, ხელოვნურ ვიზუალურ
                            სიმეტრიას ქმნის და არაბუნებრივად გამოიყურება.</p>
                    </div>

                    <div className='mb-4 mt-4'>
                        <p className='detail-text'>შემთხვევითად გენერირებული ტექსტი ეხმარება დიზაინერებს და
                            ტიპოგრაფიული ნაწარმის შემქმნელებს, რეალურთან მაქსიმალურად მიახლოებული შაბლონი წარუდგინონ
                            შემფასებელს.
                            ხშირადაა შემთხვევა, როდესაც დიზაინის შესრულებისას საჩვენებელია, თუ როგორი იქნება ტექსტის
                            ბლოკი. სწორედ ასეთ დროს არის მოსახერხებელი ამ გენერატორით შექმნილი ტექსტის გამოყენება,
                            რადგან უბრალოდ „ტექსტი ტექსტი ტექსტი“ ან სხვა გამეორებადი სიტყვების ჩაყრა, ხელოვნურ ვიზუალურ
                            სიმეტრიას ქმნის და არაბუნებრივად გამოიყურება.</p>
                    </div>


                </div>

                <div className='col-4 details-card-wrapper'>
                    <div className='details-card d-flex flex-column'>
                        <div>
                            <h2 className='detail-card-title'>დამუშავების დრო:</h2>
                            <p className='detail-card-text'>სამი სამუშაო კვირა</p>
                        </div>
                        <div className='mt-4'>
                            <h2 className='detail-card-title'>დამუშავების დრო:</h2>
                            <p className='detail-card-text'>სამი სამუშაო კვირა</p>
                        </div>
                        <div className='mt-4'>
                            <h2 className='detail-card-title'>დამუშავების დრო:</h2>
                            <p className='detail-card-text'>სამი სამუშაო კვირა</p>
                        </div>

                        <div className='mt-4'>
                            {/*<button className='make-application-btn'>განაცხადის გაკეთება</button>*/}
                            <p className='test'>განაცხადის გაკეთება</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default HoneyModuleDetail;