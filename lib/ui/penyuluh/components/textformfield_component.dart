import 'package:flutter/material.dart';
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
    return TextFormField(
      style: style,
      maxLines: maxLine,
      obscureText: obsecure,
      controller: controller,
      keyboardType: inputType,
      decoration: InputDecoration(
        isDense: true,
        filled: true,
        fillColor: ColorTheme().whiteColor,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(10),
        ),
        prefixIcon: Icon(
          icon,
          color: ColorTheme().primaryColor,
          size: 25,
        ),
        hintText: hint,
        labelText: label,
      ),
      validator: validator,
      onSaved: onSaved,
      readOnly: readOnly,
      onTap: onTap,
    );
  }
}
