import React from 'react';
import i18n from 'i18next';

const LanguageSelector = () => {
    const currentLanguage = i18n.language;

    const changeLanguage = () => {
        const newLanguage = currentLanguage === 'ka' ? 'en' : 'ka';
        i18n.changeLanguage(newLanguage);
    };

    return (
        <div className='d-flex gap-1 align-items-center' style={{cursor: 'pointer'}} onClick={changeLanguage}>
            {/*<i className="fa-solid fa-globe" style={{color: 'white'}}></i>*/}
            <i className="fa-solid fa-earth-americas"  style={{color: 'white'}}></i>
            <span style={{fontSize: '14px', color: 'white'}}>
                {currentLanguage === 'ka' ? 'EN' : 'GE'}
            </span>
        </div>
    );
};

export default LanguageSelector;