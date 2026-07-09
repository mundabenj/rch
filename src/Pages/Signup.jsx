import { useForm } from "react-hook-form";
import React from "react";
import { validateFullname, validateEmail } from "./FormValidation";

export function Signup() {
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm({
    mode: "onBlur",
  });

  const onSubmit = (data) => {
    console.log("Signup form submitted:", data);
  };

  return (
    <>
      <h1>Signup Form</h1>
      <form onSubmit={handleSubmit(onSubmit)} className="signup-form">
        <div className="mb-3">
            <label htmlFor="fullname" className="form-label">Fullname</label>
            <input
              type="text"
              className="form-control"
              id="fullname"
              name="fullname"
              placeholder="Enter your fullname"
              {...register("fullname", {
                validate: validateFullname,
              })}
            />
            {errors.fullname && <span>{errors.fullname.message}</span>}
        </div>
        <div className="mb-3">
            <label htmlFor="email" className="form-label">Email</label>
            <input
              type="email"
              className="form-control"
              id="email"
              name="email"
              placeholder="Enter your email"
              {...register("email", {
                validate: validateEmail,
              })}
            />
            {errors.email && <span className="text-danger">{errors.email.message}</span>}
        </div>
        <button type="submit" className="btn btn-primary">Submit</button>
      </form>
    </>
  );}