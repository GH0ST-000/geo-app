import React, { useState } from 'react';
import { Checkbox, FormControlLabel, TextField, Button, FormControl, InputLabel, MenuItem } from '@mui/material';
import { useFormik } from 'formik';
import * as Yup from 'yup';
import RegisterImage from '../../assets/images/HomeFirstSection.png';
import './Register.css';
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import Autocomplete from '@mui/material/Autocomplete';
import {createUser} from "../../utils/api.js";

const Register = () => {
    const [checked, setChecked] = useState(false);
    const [loading, setLoading] = useState(false);
    const { t } = useTranslation();
    const navigate = useNavigate();


    const handleSubmit = async (values) => {
        try {
            setLoading(true)
            const userData = await createUser(values);
            if(userData){
                setLoading(false)

            }
        } catch (error) {
            console.error('User creation failed:', error);
        }
    };



    const formik = useFormik({
        initialValues: {
            first_name: null,
            last_name: null,
            email: null,
            phone: null,
            password: null,
            confirmPassword: null,
            city: null
        },
        validationSchema: Yup.object({
            password: Yup.string()
                .min(6, t('passwordMinCharacter'))
                .required(t('requiredField')),
            confirmPassword: Yup.string()
                .oneOf([Yup.ref('password'), null], t('passwordDoNotMatch'))
                .required(t('requiredField')),
        }),
        onSubmit:handleSubmit,
    });

    const cities = [
        { id: 1, name: "სოხუმი" },
        { id: 2, name: "ქარელი" },
        { id: 3, name: "გორი" },
        { id: 4, name: "თბილისი" },
        { id: 5, name: "ბათუმი" },
        { id: 6, name: "ზუგდიდი" }
    ];

    return (
        <div className="register-container">
            <div className="register-form-container">
                <h3 className='create-user-title'>{t('createAccount')}</h3>
                <form onSubmit={formik.handleSubmit}>
                    <div className="name-fields-container">
                        <div className="name-field">
                            <TextField
                                label={t('firstName')}
                                variant="filled"
                                className="custom-textfield"
                                {...formik.getFieldProps('firstName')}
                            />
                        </div>

                        <div className="name-field">
                            <TextField
                                label={t('lastName')}
                                variant="filled"
                                className="custom-textfield"
                                {...formik.getFieldProps('lastName')}
                            />
                        </div>
                    </div>

                    <div>
                        <FormControl fullWidth>
                            {/*<InputLabel*/}
                            {/*    id="city-select-label"*/}
                            {/*    sx={{*/}
                            {/*        fontSize: '14px',*/}
                            {/*        color: 'black',*/}
                            {/*        '&.Mui-focused': {*/}
                            {/*            color: '#348E38',*/}
                            {/*        },*/}
                            {/*    }}*/}
                            {/*>*/}
                            {/*    {t('city')}*/}
                            {/*</InputLabel>*/}

                            <Autocomplete
                                id="city-select"
                                value={formik.values.city}
                                onChange={(event, newValue) => formik.setFieldValue('city', newValue)}
                                className="custom-textfield"
                                options={cities}
                                getOptionLabel={(option) => option.name}
                                renderInput={(params) => (
                                    <TextField
                                        {...params}
                                        label={t('city')}
                                        variant="filled"
                                        sx={{
                                            "& .MuiFilledInput-root": {
                                                backgroundColor: "#EFF1F4",
                                            },
                                            "& .MuiFilledInput-root:hover": {
                                                backgroundColor: "#E0E3EB",
                                            },
                                            "& .MuiFilledInput-root.Mui-focused": {
                                                backgroundColor: "#D4D9E2",
                                            },
                                            "& .MuiInputBase-input": {
                                                color: "black",
                                            },
                                            "& .MuiInputLabel-root": {
                                                color: "black",
                                            },
                                            "&.Mui-focused .MuiInputLabel-root": {
                                                color: "red",
                                            },
                                        }}
                                    />
                                )}
                                filterOptions={(options, state) =>
                                    options.filter(option =>
                                        option.name.toLowerCase().includes(state.inputValue.toLowerCase())
                                    )
                                }
                            />
                        </FormControl>

                    </div>

                    <div className="form-field mt-3">
                        <TextField
                            label={t('mail')}
                            variant="filled"
                            className="custom-textfield"
                            {...formik.getFieldProps('email')}
                        />
                    </div>
                    <div className="form-field">
                        <TextField
                            label={t('mobileNumber')}
                            variant="filled"
                            className="custom-textfield"
                            {...formik.getFieldProps('phone')}
                        />
                    </div>

                    <div className="form-field">
                        <TextField
                            label={t("password")}
                            variant="filled"
                            type={checked ? 'text' : 'password'}
                            className="custom-textfield"
                            {...formik.getFieldProps('password')}
                            error={formik.touched.password && Boolean(formik.errors.password)}
                            helperText={formik.touched.password && formik.errors.password}
                        />
                    </div>

                    <div className="form-field">
                        <TextField
                            label={t('confirmPassword')}
                            variant="filled"
                            type={checked ? 'text' : 'password'}
                            className="custom-textfield"
                            {...formik.getFieldProps('confirmPassword')}
                            error={formik.touched.confirmPassword && Boolean(formik.errors.confirmPassword)}
                            helperText={formik.touched.confirmPassword && formik.errors.confirmPassword}
                        />
                    </div>

                    <div className='checkbox-container'>
                        <FormControlLabel
                            control={
                                <Checkbox
                                    sx={{'&.Mui-checked': {color: '#348e38'}}}
                                    checked={checked}
                                    onChange={() => setChecked(!checked)}
                                />
                            }
                            label={t('showPassword')}
                        />
                    </div>

                    {
                        loading ?  (
                            <button className="btn btn-primary loading-btn" type="button" disabled >
                                <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </button>
                        ) : (
                            <button type="submit" className='register-btn'>
                                {t('registration')}
                            </button>
                        )
                    }


                    <div className='auth-redirect'>
                        <p className='already-have-account'>
                        {t('alreadyHaveAnAccount')} <span className='auth-text'
                                                              onClick={() => navigate('/authentication')}>{t('authorization')}</span>
                        </p>
                    </div>
                </form>
            </div>

            <div className="register-image-container">
                <img src={RegisterImage} alt='register' className='register-image'/>
            </div>
        </div>
    );
};

export default Register;
