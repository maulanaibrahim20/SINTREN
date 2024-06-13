import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';

class TextFormFieldComponent extends StatelessWidget {
  final dynamic controller;
  final IconData icon;
  final String hint;
  final String label;
  final dynamic validator;
  final dynamic onSaved;
  final TextInputType inputType;
  final bool obsecure;
  final dynamic maxLine;
  final bool readOnly;
  final dynamic onTap;
  final dynamic style;

  const TextFormFieldComponent({
    super.key,
    required this.controller,
    required this.icon,
    required this.hint,
    required this.label,
    required this.validator,
    this.inputType = TextInputType.name,
    this.obsecure = false,
    this.maxLine,
    this.onSaved,
    this.readOnly = false,
    this.onTap,
    this.style,
  });

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 60.h, 
      child: TextFormField(
        style: style ?? TextStyle(fontSize: 14.sp),
        maxLines: maxLine ?? 1,
        obscureText: obsecure,
        controller: controller,
        keyboardType: inputType,
        decoration: InputDecoration(
          contentPadding:
              EdgeInsets.symmetric(vertical: 15.h, horizontal: 10.w),
          isDense: false,
          filled: true,
          fillColor: ColorTheme().whiteColor,
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10.r),
          ),
          prefixIcon: Icon(
            icon,
            color: ColorTheme().primaryColor,
            size: 25.sp,
          ),
          hintText: hint,
          hintStyle: TextStyle(fontSize: 14.sp),
          labelText: label,
          labelStyle: TextStyle(fontSize: 14.sp),
        ),
        validator: validator,
        onSaved: onSaved,
        readOnly: readOnly,
        onTap: onTap,
      ),
    );
   }
}
