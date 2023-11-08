/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import FamilyMemberCardComponent from "./FamilyMemberCard.vue";

describe("FamilyMemberCardComponent", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(FamilyMemberCardComponent);
        expect(wrapper.exists()).toBe(true);
    });
});
